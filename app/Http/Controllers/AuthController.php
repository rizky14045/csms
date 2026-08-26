<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helper\PasswordHelper;
use App\Http\Validation\AuthValidation;
use App\Models\PasswordHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;
use App\Services\Auth\ForgotPasswordService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected function validator(array $data, $validation, array $messages = [])
    {
        return Validator::make($data, $validation, $messages);
    }
    
    public function getLogin(){
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $validator = $this->validator(
            $request->all(),
            AuthValidation::rulesForLogin(),
            AuthValidation::messages()
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Alert::error('Login gagal', 'Email atau password salah!!');
            return redirect()->route('login');
        }

        if ($user->locked_until && now()->lessThan($user->locked_until)) {
            $diff = now()->diffInMinutes($user->locked_until);
            Alert::warning('Warning', "Akun dikunci. Coba lagi dalam {$diff} menit.");
            return redirect()->back();
        }

        $ldapData = $user->login_type == 1
            ? $this->authenticateViaLdap($request->email, $request->password)
            : null;

        if (!$ldapData && !Hash::check($request->password, $user->password)) {

            $user->increment('access_failed_count');

            if ($user->access_failed_count >= 3) {
                $user->update([
                    'locked_until' => now()->addMinutes(5),
                    'access_failed_count' => 0,
                ]);

                Alert::warning('Akun Dikunci', 'Akun dikunci selama 5 menit.');
                return redirect()->route('login');
            }

            Alert::error('Login gagal', 'Email atau password salah!!');
            return redirect()->route('login')->withInput();
        }

        if ($ldapData) {
            $this->syncUserFromLdap($user, $ldapData);
        }

        Auth::login($user, true);

        $request->session()->regenerate();

        $user->update([
            'access_failed_count' => 0,
            'locked_until' => null,
        ]);

        Alert::success('Login Berhasil', 'User berhasil login!');

        return redirect()->route('dashboard');
    }

    /**
     * Authenticate against the LDAP API. Returns the decoded response
     * (including userdetail) only when the API explicitly reports
     * valid credentials (valid === 1) — an HTTP 200 alone is not
     * proof of a valid login, this API returns 200 for failed
     * credentials too and signals success only via the "valid" field.
     */
    private function authenticateViaLdap(string $username, string $password): ?array
    {
        try {
            $response = Http::asMultipart()
                ->timeout(5)
                ->post(config('services.ldap.auth_url'), [
                    ['name' => 'username', 'contents' => $username],
                    ['name' => 'password', 'contents' => $password],
                ]);

            if (!$response->successful() || (int) $response->json('valid') !== 1) {
                return null;
            }

            return $response->json();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Sync the local user record with the latest data returned by LDAP.
     * "jabatan" is intentionally not touched here: the LDAP API response
     * doesn't include any job-title/jabatan field, so there is nothing
     * to sync it from.
     */
    private function syncUserFromLdap(User $user, array $ldapData): void
    {
        $detail = $ldapData['userdetail'] ?? [];

        try {
            $user->update([
                'nid' => $ldapData['nid'] ?? $user->nid,
                'unit_code' => $detail['unit']['0'] ?? $user->unit_code,
                'name' => $detail['displayname']['0'] ?? $user->name,
            ]);
        } catch (\Throwable $e) {
            // Sync is best-effort; never block login because of it.
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::success('Logout Berhasil', 'User berhasil logout!');
        return redirect()->route('login');
    }

    public function getForgotPassword(){
        return view('auth.forgot-password');
    }

    public function sendResetLink(
        Request $request,
        ForgotPasswordService $service
    ) {
        // Validation rules
        $validator = $this->validator($request->all(), AuthValidation::rulesForForgotPassword(), AuthValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $service->sendResetLink($request->email);

        // Logic to send reset link goes here

        Alert::success('Berhasil', 'Link reset password telah dikirim ke email Anda.');
        return redirect()->route('login');
    }

    public function getResetPassword($token){
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(
        Request $request,
        ForgotPasswordService $service
    ) {

        // Validation rules
        $validator = $this->validator($request->all(), AuthValidation::rulesForResetPassword(), AuthValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();
        $isAllowed = PasswordHelper::isPasswordAllowed(
                    $user->id,
                    $request->password
                );

        if (!$isAllowed) {
            Alert::warning(
                'Peringatan',
                'Password baru tidak boleh sama dengan password sebelumnya.'
            );
            return redirect()->back();
        }
        
        $success = $service->resetPassword(
            $request->email,
            $request->password,
            $request->token
        );

        if (!$success) {
            Alert::error('Error', 'Token reset password tidak valid atau kadaluarsa.');
            return back();
        }

        Alert::success('Success', 'Password berhasil direset.');
        return redirect()->route('login');
    }

    public function editProfile(){
        return view('auth.edit-profile');
    }

    public function updateProfile(Request $request){
        $user = Auth::user();

        // Validation rules
        $validator = $this->validator($request->all(), AuthValidation::rulesForUpdateProfile($user->id), AuthValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $isAllowed = PasswordHelper::isPasswordAllowed(
                    $user->id,
                    $request->password
                );

        if (!$isAllowed) {
            Alert::warning(
                'Peringatan',
                'Password baru tidak boleh sama dengan password sebelumnya.'
            );
            return redirect()->back();
        }

        // check old password
        if (!Hash::check($request->old_password, $user->password)) {
            Alert::error('Error', 'Password lama tidak sesuai.');
            return redirect()->back();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        PasswordHistory::create([
            'user_id' => $user->id,
            'password_hash' => $user->password,
        ]);

        Alert::success('Success', 'Profile berhasil diupdate.');
        return redirect()->route('profile.edit');
    }
}

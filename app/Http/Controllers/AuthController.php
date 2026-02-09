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
    
    public function login(Request $request){

        // Validation rules
        $validator = $this->validator($request->all(), AuthValidation::rulesForLogin(), AuthValidation::messages());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if(!$user){
            Alert::error('Login gagal','Email atau password salah!!');
            return redirect()->route('login');
        }
        if ($user->locked_until && now()->lessThan($user->locked_until)) {

            $diff = number_format(now()->diffInMinutes($user->locked_until));
            Alert::warning('Warning', "Akun dikunci. Coba lagi dalam {$diff} menit.");
            return redirect()->back();
        }

        if( Hash::check($request->password,$user->password) ){
            if (
                $user->session_id &&
                $user->session_expired_date &&
                now()->lessThan($user->session_expired_date)
            ) {
                Alert::warning(
                    'Login Ditolak',
                    'Akun sedang aktif di perangkat lain.'
                );
                return redirect()->route('login');
            }
            Auth::login($user,true);

            $user->update([
                'session_id' => session()->getId(),
                'session_expired_date' => now()->addMinutes(config('session.lifetime')),
                'access_failed_count' => 0,
                'locked_until' => null,
            ]);
            
            Alert::success('Login Berhasil', 'User berhasil login!');
            if($user->type == 'admin'){
                return redirect()->route('admin.home.index');
            }elseif($user->type == 'user'){
                return redirect()->route('user.home.index');
            }elseif($user->type == 'bujp'){
                return redirect()->route('bujp.home.index');
            }   

        }else{
            $user->access_failed_count++;
            $user->save();

            if ($user->access_failed_count >= 3) {
                $user->locked_until = now()->addMinutes(5);
                $user->access_failed_count = 0; // Reset counter after locking
                $user->save();

                Alert::warning('Akun Dikunci','Karena kesalahan input password beberapa kali, akun dikunci selama 5 menit!.');
                return redirect()->route('login');
            }
            
            Alert::error('Login gagal','Email atau password salah!!');
            return redirect()->route('login')->withInput();

        }
    }

    public function logout(Request $request){

        if (Auth::check()) {
            $user = Auth::user();

            $user->update([
                'session_id' => null,
                'session_expired_date' => null,
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

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

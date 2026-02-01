<?php

namespace App\Http\Controllers\Bujp;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function getLogin(){
        return view('bujp.login');
    }
    
    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus berformat email yang valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user){
            Alert::error('Login gagal','Email atau password salah!!');
            return redirect()->route('bujp.login');
        }
        if ($user->locked_until && now()->lessThan($user->locked_until)) {

            $diff = number_format(now()->diffInMinutes($user->locked_until));
            Alert::warning('Warning', "Akun dikunci. Coba lagi dalam {$diff} menit.");
            return redirect()->back();
        }

        if( Hash::check($request->password,$user->password) ){

            Auth::login($user,true);
            Alert::success('Login Berhasil', 'User berhasil login!');
            return redirect()->route('bujp.home.index');

        }else{
            $user->access_failed_count++;
            $user->save();

            if ($user->access_failed_count >= 3) {
                $user->locked_until = now()->addMinutes(5);
                $user->access_failed_count = 0; // reset counter setelah dikunci
                $user->save();

                Alert::warning('Akun Dikunci','Karena kesalahan input password beberapa kali, akun dikunci selama 5 menit!.');
                return redirect()->route('bujp.login');
            }
            
            Alert::error('Login gagal','Email atau password salah!!');
            return redirect()->route('bujp.login');

        }
    }

    public function logout(Request $request){

        Auth::logout();

        Alert::success('Logout Berhasil', 'User berhasil logout!');
        return redirect()->route('bujp.login');
    }
}

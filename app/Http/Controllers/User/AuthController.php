<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function getLogin(){
        return view('user.login');
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
            return redirect()->route('user.login');
        }
        if ($user->locked_until && now()->lessThan($user->locked_until)) {

            $diff = number_format(now()->diffInMinutes($user->locked_until));
            Alert::warning('Warning', "Akun dikunci. Coba lagi dalam {$diff} menit.");
            return redirect()->back();
        }

        if( Hash::check($request->password,$user->password) ){
            Auth::login($user,true);
            Alert::success('Login Berhasil', 'User berhasil login!');
            return redirect()->route('user.home.index');

        }else{
            $admin->access_failed_count++;
            $admin->save();

            if ($admin->access_failed_count >= 3) {
                $admin->locked_until = now()->addMinutes(5);
                $admin->access_failed_count = 0; // reset counter setelah dikunci
                $admin->save();

                Alert::warning('Akun Dikunci','Karena kesalahan input password beberapa kali, akun dikunci selama 5 menit!.');
                return redirect()->route('user.login');
            }
            
            Alert::error('Login gagal','Email atau password salah!!');
            return redirect()->route('user.login');

        }
    }

    public function logout(Request $request){

        Auth::logout();

        Alert::success('Logout Berhasil', 'User berhasil logout!');
        return redirect()->route('user.login');
    }
}

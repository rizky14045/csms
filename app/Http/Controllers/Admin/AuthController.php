<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function getLogin(){
        return view('admin.login');
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

        $admin = User::where('type','admin')->where('email', $request->email)->first();
        if(!$admin){
            Alert::warning('Login gagal','Email atau password salah!!');
            return redirect()->route('admin.login');
        }
        if ($admin->locked_until && now()->lessThan($admin->locked_until)) {

            $diff = number_format(now()->diffInMinutes($admin->locked_until));
            Alert::warning('Warning', "Akun dikunci. Coba lagi dalam {$diff} menit.");
            return redirect()->back();
        }

        if( Hash::check($request->password,$admin->password) ){

            Auth::login($admin,true);
            Alert::success('Login Berhasil', 'Admin berhasil login!');
            return redirect()->route('admin.home.index');

        }else{

            $admin->access_failed_count++;
            $admin->save();

            if ($admin->access_failed_count >= 3) {
                $admin->locked_until = now()->addMinutes(5);
                $admin->access_failed_count = 0; // reset counter setelah dikunci
                $admin->save();

                Alert::warning('Akun Dikunci','Karena kesalahan input password beberapa kali, akun dikunci selama 5 menit!.');
                return redirect()->route('admin.login');
            }
            
            Alert::warning('Login gagal','Email atau password salah!!');
            return redirect()->route('admin.login');

        }
    }

    public function logout(Request $request){

        Auth::logout();

        Alert::success('Logout Berhasil', 'Admin berhasil logout!');
        return redirect()->route('admin.login');
    }
}

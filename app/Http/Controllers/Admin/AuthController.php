<?php

namespace App\Http\Controllers\Admin;

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

        $admin = Admin::where('email', $request->email)->first();

        if(!$admin){
            Alert::error('Login gagal','Email atau password salah!!');
            return redirect()->route('admin.login');
        }

        if( Hash::check($request->password,$admin->password) ){

            Auth::guard('admin')->login($admin);
            Alert::success('Login Berhasil', 'Admin berhasil login!');
            return redirect()->route('admin.home.index');

        }else{
            
            Alert::error('Login gagal','Email atau password salah!!');
            return redirect()->route('admin.login');

        }
    }

    public function logout(Request $request){

        Auth::guard('admin')->logout();

        Alert::success('Logout Berhasil', 'Admin berhasil logout!');
        return redirect()->route('admin.login');
    }
}

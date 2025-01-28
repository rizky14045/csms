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

        if( Hash::check($request->password,$user->password) ){
            $remember = $request->has('remember') ? true : false;
            Auth::guard('web')->login($user,$remember);
            Alert::success('Login Berhasil', 'User berhasil login!' ,$remember);
            return redirect()->route('user.home.index');

        }else{
            
            Alert::error('Login gagal','Email atau password salah!!');
            return redirect()->route('user.login');

        }
    }

    public function logout(Request $request){

        Auth::guard('web')->logout();

        Alert::success('Logout Berhasil', 'User berhasil logout!');
        return redirect()->route('user.login');
    }
}

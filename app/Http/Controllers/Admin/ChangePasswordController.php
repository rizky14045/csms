<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ChangePasswordController extends Controller
{
    public function changePassword(){
        return view('admin.change-password');
    }
    public function updatePassword(Request $request){
        
        $this->validate($request, [
            'old_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
            ],
            'repeat_password' => 'string|required_with:new_password|same:new_password'
        ],[
            'old_password.required' => 'Password lama harus di isi!',
            'old_password.string' => 'Password lama harus di isi!',
            'new_password.required' => 'Password Baru harus di isi!',
            'new_password.string' => 'Password Baru harus di isi!',
            'repeat_password.required_new' => 'Password Baru harus di isi!',
            'repeat_password.string' => 'Password Baru harus di isi!',
            'repeat_password.same' => 'Kata sandi tidak cocok!',
        ]);

        $admin = Admin::find(Auth::guard('admin')->user()->id);
        if( Hash::check($request->old_password,$admin->password) ){
            $admin->password = bcrypt($request->new_password);
            $admin->save();

            Alert::success('Berhasil', 'Berhasil merubah password!');
            return redirect()->back();
        } else {
            Alert::error('Gagal', 'Gagal merubah password!');
            return redirect()->back();
        }

    }
}

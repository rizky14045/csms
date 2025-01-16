<?php

namespace App\Http\Controllers\Bujp;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ChangePasswordController extends Controller
{
    public function changePassword(){
        return view('bujp.change-password');
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

        $user = Vendor::find(Auth::guard('vendor')->user()->id);
        if( Hash::check($request->old_password,$user->password) ){
            $user->password = bcrypt($request->new_password);
            $user->save();

            Alert::success('Berhasil', 'Berhasil merubah password!');
            return redirect()->back();
        } else {
            Alert::error('Gagal', 'Gagal merubah password!');
            return redirect()->back();
        }

    }
}

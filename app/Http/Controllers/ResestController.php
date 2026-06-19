<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResestController extends Controller
{
    public function reset()
    {
        return view('resetpassword'); 
    }

    public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);
    $teacher = Teacher::where('email',$request->email)->first();
    if(!$teacher){
        return back()->with("error","Your Email is not allowed to reset password,please contact to the administrator");
    }

    $status = Password::broker('teachers')->sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Link sent successfully, check your email Inbox')
        : back()->withErrors(['email' => 'Email not found.']);
}

   
    public function showResetForm(Request $request, $token = null)
    {
        return view('resetpassword1', [
            'token' => $token,
            'email' => $request->query('email') 
        ]);
    }

    
    public function updatePassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed'
    ]);

    $status = Password::broker('teachers')->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($teacher, $password) {
            $teacher->password = Hash::make($password);
            $teacher->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('again')->with('success', 'Password changed success!')
        : back()->withErrors(['email' => __($status)]);
}
}

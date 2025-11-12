<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth/register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function showLogin()
    {
        return view('auth/login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (empty($user->postcode) || empty($user->address)) {
                return redirect('/mypage/profile');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors(['login' => 'ログイン情報が登録されていません'])->onlyInput('email');
    }



    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showVerificationNotice()
    {
        return view('auth.verification_email');
    }

    public function verificationEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('profile.edit');
    }

    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', '認証メールを再送しました。MailHogを確認してください。');
    }
}
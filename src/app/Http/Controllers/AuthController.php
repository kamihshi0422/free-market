<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Models\User;

use Illuminate\Support\Facades\Hash; //パスワードをハッシュ(暗号)化
use Illuminate\Support\Facades\Auth; //認証
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    // 会員登録　画面表示
    public function showRegister()
    {
        return view('auth/register');
    }

    // 会員登録機能
    public function register(RegisterRequest $request)
    {
        // バリデーション済みデータを取得
        $data = $request->validated();

        // ユーザー作成
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        // 登録後ログイン
        Auth::login($user);

        // 初回登録直後はプロフィール設定ページへリダイレクト
        return redirect()->route('verification.notice');
    }

    // ログイン画面表示
    public function showLogin()
    {
        return view('auth/login');
    }

    // ログイン機能
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // プロフィール未設定ならプロフィール設定ページへ
            if (empty($user->postcode) || empty($user->address)) {
                return redirect('/mypage/profile');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません',
        ])->onlyInput('email');
    }

    // ログアウト機能
    public function logout(Request $request)
    {
        Auth::logout();

        // セッションを無効化する
        $request->session()->invalidate();
        // CSRFトークンを再生成
        $request->session()->regenerateToken();

        return redirect('/login');
    }

  // 認証メール送信完了画面
    public function showVerifyNotice()
    {
        return view('auth.verify-email');
    }

    // メール内リンククリック時の処理
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill(); // 認証完了処理
        return redirect()->route('profile.edit');
    }

    // 認証メール再送
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        $request->user()->sendEmailVerificationNotification();

        return redirect('http://localhost:8025')->with('message', '認証メールを送信しました！Mailhogを確認してください。');
    }
}
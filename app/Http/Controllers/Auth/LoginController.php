<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\LoggedInUser;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    use AuthenticatesUsers {
        login as _login;
        logout as _logout;
    }

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(LoginRequest $request)
    {
        // ログイン後と画面遷移の間に処理をはさみたいので返り値を一旦変数に入れる
        $response = $this->_login($request);

        // ログイン管理テーブルにデータを追加
        LoggedInUser::create([
            'user_id' => \Auth::id()
        ]);

        // レスポンスを返す
        return $response;
    }

    public function logout(Request $request)
    {
        // ログアウト前にデータを削除
        LoggedInUser::where('user_id', \Auth::id())->delete();

        // 既存のログアウト処理を実行
        return $this->_logout($request);
    }
}

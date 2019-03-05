<?php

namespace App\Http\Requests;

use App\LoggedInUser;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                function ($attribute, $value, $fail) {
                    $numberOfLoginUser = LoggedInUser::count();
                    $isLoggedIn = LoggedInUser::where('user_id', \Auth::id())->exists();
                    // ログインユーザーが2名いる場合で、自身がまだログインしていなければエラーを返す
                    if ($numberOfLoginUser > 2 && !$isLoggedIn) {
                        return $fail(trans('validation.custom.limit-login'));
                    }
                }
            ]
        ];
    }
}

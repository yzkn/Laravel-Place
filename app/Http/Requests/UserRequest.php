<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Log; // ログ出力で使用

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Log::info('UserRequest::authorize()');

        if ($this->path()==='user' || strpos($this->path(), 'user/') === 0) {
            return true;
        }

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }
        Log::info('path: '.$this->path());

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5',
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'必須項目です。',
            'name.min'=>'3文字以上入力してください。',
            'email.required'=>'必須項目です。',
            'email.email'=>'メールアドレスを入力してください。',
            'email.unique'=>'ユニークな値を入力してください。',
            'password.required'=>'必須項目です。',
            'password.confirmed'=>'確認用パスワードと一致しません。',
            'password.min'=>'ユニークな値を入力してください。'
        ];
    }
}

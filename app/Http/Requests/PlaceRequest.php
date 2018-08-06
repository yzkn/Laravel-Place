<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Log; // ログ出力で使用

class PlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Log::info('PlaceRequest::authorize()');

        if ($this->path()==='place' || strpos($this->path(), 'place/') === 0) {
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
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:0,180'
        ];
    }

    public function messages()
    {
        return [
            'lat.required'=>'必須項目です。',
            'lat.numeric'=>'数値を入力してください。',
            'lat.between'=>'-90から90の範囲で入力してください。',
            'lng.required'=>'必須項目です。',
            'lng.numeric'=>'数値を入力してください。',
            'lng.between'=>'0から180の範囲で入力してください。'
        ];
    }
}

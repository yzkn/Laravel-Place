<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path()==='place' || strpos($this->path(), 'place/') === 0) {
            return true;
        }
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'img' => ['image','mimes:jpeg,png'],
            'name' => ['required','max:20'],
            'postcode' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'img.image' => '画像ファイルを選択してください',
            'img.mimes:jpeg,png' =>'画像は.jpeg もしくは .png を選択してください',
            'name.required' => 'お名前を入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex'    => '郵便番号は「123-4567」の形式で入力してください',
            'address.required'  => '住所を入力してください',
        ];
    }
}

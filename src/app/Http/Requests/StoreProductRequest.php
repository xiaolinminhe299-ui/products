<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0', 'max:10000'],
            'image' => ['required', 'file', 'mimes:png,jpeg'],
            'description' => ['required', 'string', 'max:120'],
            'seasons' => ['required', 'array', 'min:1'],
            'seasons.*' => ['exists:seasons,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'name' => '商品名',
            'price' => '値段',
            'image' => '商品画像',
            'description' => '商品説明',
            'seasons' => '季節',
            'seasons.*' => '季節',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'required' => ':attributeを入力してください。',
            'integer' => ':attributeは整数で入力してください。',
            'min.numeric' => ':attributeは:min以上で入力してください。',
            'max.numeric' => ':attributeは:max以下で入力してください。',
            'max.string' => ':attributeは:max文字以内で入力してください。',
            'mimes' => ':attributeは.pngまたは.jpeg形式でアップロードしてください。',
            'array' => ':attributeを選択してください。',
            'exists' => '選択された:attributeが正しくありません。',
        ];
    }
}

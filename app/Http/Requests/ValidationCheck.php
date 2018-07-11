<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationCheck extends FormRequest
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
            'title' => 'required|min:3|max:30',
            'content' => 'required',
            /* 'comment' => 'required', */
        ];
    }

    public function messages() {
        return [
            'title.required' => 'タイトルを入力してください',
            'title.min' => 'タイトルは３文字以上を入力してください',
            'title.max' => 'タイトルは３０文字以内で入力してください',
            'content.required' => '本文を入力してください',
            /* 'comment.required' => '本文を入力してください', */
        ];
    }
}

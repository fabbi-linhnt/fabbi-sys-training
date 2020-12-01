<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'required|min:3|max:50',
            'email' => 'required|min:10|max:50|unique:users',
            'password' => 'required|min:6|max:20',
            'phone' => 'required|numeric|digits:10|unique:users',
            'address' => 'required',
            'course_id' => 'required|array',
            'course_id.*' => 'exists:courses,id',
            'birthday' => 'required',
            'img_path' => 'required',
        ];
    }
}

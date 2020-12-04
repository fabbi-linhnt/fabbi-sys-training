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
        $id = $this->user;
        return [
            'name' => 'required|min:3|max:50',
            'email' => 'required|min:10|max:50|unique:users,email,' . $id,
            'password' => 'required|min:6|max:20',
            'phone' => 'required|numeric|digits:10|unique:users,phone,' . $id,
            'address' => 'required',
            'course_id' => 'required|array',
            'course_id.*' => 'exists:courses,id',
            'birthday' => 'required'
        ];
    }
}

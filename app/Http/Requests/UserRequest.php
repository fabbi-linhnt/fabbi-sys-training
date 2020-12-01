<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'course_id' => 'required',
            'birthday' => 'required',
            'img_path' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name has at least 3 chracters',
            'name.max' => 'Name has no more than 50 chracters',
            'email.required' => 'Email is required',
            'email.min' => 'Email has at least 10 characters',
            'email.max' => 'Email has no more than 50 characters',
            'email.unique' => 'Email is unique',
            'password.required' => 'Password is required',
            'password.min' => 'Password has at least 6 characters',
            'password.max' => 'Password has no more than 20 characters',
            'phone.required' => 'Phone is required',
            'phone.numeric' => 'Phone contains only number',
            'phone.digits' => 'Phone has exactly 10 digits',
            'phone.unique' => 'Phone is unique',
            'address.required' => 'Address is required',
            'birthday.required' => 'Birthday is required',
            'img_path.required' => 'Img_path is required',
            'course_id.required' => 'Coure_id is required',
        ];
    }
}

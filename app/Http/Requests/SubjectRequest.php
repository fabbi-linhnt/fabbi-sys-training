<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
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
            'name' => 'required|min:5|max:20',
            'description' => 'required|min:5',
            'course_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name has at least 5 characters',
            'name.max' => 'Name has no more than 20 characters',
            'description.required' => 'Description is required',
            'description.min' => 'Description has at least 5 characters',
            'course_id.required' => 'Course_id is required',
        ];
    }
}

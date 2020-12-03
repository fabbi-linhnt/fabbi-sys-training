<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class TasksStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'task.name' => 'required|max:255',
            'task.description' => 'required|max:255',
            'task.content' => 'required',
            'task.deadline' => 'required|date',
            'task.is_active' => 'required|boolean',
            'subject_id' => 'required|array',
            'subject_id.*' => 'exists:subjects,id',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users, id'
        ];
    }
}

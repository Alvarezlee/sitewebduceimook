<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quiz_subject_id' => ['required', 'exists:quiz_subjects,id'],
            'question' => ['required', 'string', 'max:2000'],
            'explanation' => ['nullable', 'string', 'max:2000'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'options' => ['required', 'array', 'size:4'],
            'options.*.label' => ['required', 'string', 'max:255'],
            'correct_option' => ['required', 'integer', 'min:0', 'max:3'],
        ];
    }
}

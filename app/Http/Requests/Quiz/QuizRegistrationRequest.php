<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class QuizRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'institution' => ['required', 'string', 'max:150'],
            'class_level' => ['required', 'string', 'max:50'],
            'region' => ['required', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:100'],
            'arrondissement' => ['required', 'string', 'max:100'],
            'parent_name' => ['required', 'string', 'max:150'],
            'parent_phone' => ['required', 'string', 'max:30'],
            'phone' => ['required', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}

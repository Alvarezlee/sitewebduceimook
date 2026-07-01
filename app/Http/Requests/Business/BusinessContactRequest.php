<?php

namespace App\Http\Requests\Business;

use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class BusinessContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:3000'],
            'g-recaptcha-response' => [new RecaptchaRule],
        ];
    }
}

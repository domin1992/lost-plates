<?php

namespace App\Http\Requests;

use App\Rules\EmailOrPhoneNumberValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MarkerSubmitContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'contact' => ['required', 'string', 'max:191', new EmailOrPhoneNumberValidationRule],
        ];
    }
}

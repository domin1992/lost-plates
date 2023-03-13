<?php

namespace App\Http\Requests;

use App\Models\Marker;
use App\Rules\PhoneNumberValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MarkerStoreRequest extends FormRequest
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
            'type' => 'required|string|in:' . Marker::TYPE_FOUND . ',' . Marker::TYPE_LOST,
            'plate_number' => 'required|string|min:3|max:191',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'phone_number' => ['nullable', 'string', 'max:191', new PhoneNumberValidationRule],
            'email' => 'nullable|email|max:191',
            'radius' => 'nullable|numeric',
            'additional_info' => 'nullable|string',
            'media' => 'nullable|array|max:5',
            'media.*' => 'nullable|string|exists:media,id',
        ];
    }
}

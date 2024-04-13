<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkersIndexRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plateNumer' => 'sometimes|string',
            'corners' => 'sometimes|array',
            'corners.nwLat' => 'required_with:corners|numeric',
            'corners.nwLng' => 'required_with:corners|numeric',
            'corners.seLat' => 'required_with:corners|numeric',
            'corners.seLng' => 'required_with:corners|numeric',
            'type' => 'sometimes|string',
            'paginate' => 'sometimes|boolean',
        ];
    }
}

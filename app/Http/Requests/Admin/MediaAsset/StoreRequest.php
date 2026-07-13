<?php

namespace App\Http\Requests\Admin\MediaAsset;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'images' => ['required', 'array'],
            'images.*' => ['image', 'max:5120'],
            'alt' => ['nullable', 'string', 'max:255'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin\Param;

use App\Enums\Param\FilterType;
use App\Enums\Param\InputType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'title' =>  'required|string|max:255',
            'filter_type' =>  ['required', new Enum(FilterType::class)],
            'input_type' =>  ['required', new Enum(InputType::class)],
            'is_variant' => 'required|boolean',
        ];
    }
}

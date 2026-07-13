<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' =>  'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id',
        ];
    }
}

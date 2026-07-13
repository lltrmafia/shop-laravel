<?php

namespace App\Http\Requests\Admin\Product;

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
            'product.title' => 'required|string|max:255',
            'product.article' => 'required|string|max:255|unique:products,article',
            'product.description' => 'required|string',
            'product.content' => 'required|string',
            'product.price' => 'required|numeric',
            'product.parent_id' => 'nullable|exists:products,id',
            'product.old_price' => 'required|numeric',
            'product.qty' => 'required|integer',
            'product.category_id' => 'required|integer|exists:categories,id',
            'product.product_group_id' => 'required|integer|exists:product_groups,id',
            'media' => 'nullable|array',
            'params' => 'nullable|array',
            'params.*.id' => 'required|integer|exists:params,id',
            'params.*.value' => 'required|string',
        ];
    }
}

<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'filter_type' => $this->filter_type->value,
            'filter_type_name' => $this->filter_type_name,
            'input_type' => $this->input_type_value,
            'input_type_name' => $this->input_type_name,
            'is_variant' => $this->is_variant
        ];
    }
}

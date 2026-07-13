<?php

namespace App\Models;

use App\Enums\Param\FilterType;
use App\Enums\Param\InputType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Param extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'filter_type',
        'input_type',
    ];
    protected $casts = [
        'filter_type' => FilterType::class,
        'input_type' => InputType::class,
    ];

    public function getFilterTypeNameAttribute()
    {
        return $this->filter_type->cyrillic();
    }

    public function getFilterTypeValueAttribute()
    {
        return $this->filter_type?->value;
    }



    public function getInputTypeNameAttribute()
    {
        return Str::lower($this->input_type?->name);
    }

    public function getInputTypeValueAttribute()
    {
        return $this->input_type?->value;
    }
}

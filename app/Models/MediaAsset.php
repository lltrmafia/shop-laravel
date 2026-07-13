<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends Model
{
    protected $fillable = [
        'disk',
        'path',
        'filename',
        'mime_type',
        'size',
        'width',
        'height',
        'alt',
        'created_by',
    ];

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'media_asset_product'
        )
            ->withPivot([
                'collection',
                'sort_order'
            ])
            ->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getImageUrlAttribute()
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getPivotProductsCountAttribute()
    {
        return $this->products()->count();
    }
}

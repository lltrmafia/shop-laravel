<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'content',
        'price',
        'old_price',
        'qty',
        'parent_id',
        'product_group_id',
        'category_id',
        'slug',
        'article',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($post) {
            $slug = Str::slug($post->title);
            $original = $slug;
            $count = 1;

            while (Product::where('slug', $slug)->exists()) {
                $slug = $original . '-' . $count++;
            }

            $post->slug = $slug;

        });
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function children(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function trashedChildren(): HasMany
    {
        return $this->hasMany(Product::class, 'parent_id')->onlyTrashed();
    }

    public function media()
    {
        return $this->belongsToMany(
            MediaAsset::class,
            'media_asset_product'
        )
            ->withPivot([
                'collection',
                'sort_order'
            ])
            ->withTimestamps();
    }

    public function params()
    {
        return $this->belongsToMany(
            Param::class,
            'param_product'
        )
            ->withPivot([
                'value',
                'display_name'
            ])
            ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productGroup(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class);
    }

    public function getImagePreviewAttribute() : ?array
    {
        $media = $this->media()->wherePivot('collection', 'preview')->first();
        if(!$media) {
            return null;
        }
        return [
            'url' => Storage::disk('public')->url($media->path),
            'alt' => $media->alt
        ];
    }

    public function getProductGalleryAttribute() : ?array
    {
        $media = $this->media()->wherePivot('collection', 'gallery')->get();
        if(!$media) {
            return null;
        }
        return $media->map(function ($media) {
            return [
                'url' => Storage::disk('public')->url($media->path),
                'alt' => $media->alt
            ];
        })->toArray();
    }

    public function getProductMediaAttribute(): ?array
    {
        $relation = $this->media;
        $gallery = $relation->filter(function ($item) {
            return $item->pivot->collection === 'gallery';
        });
        $preview = $relation->firstWhere('pivot.collection', 'preview');

        $media = [$preview, ...$gallery];
        return collect($media)->map(function ($item) {
            return [
                'url' => Storage::disk('public')->url($item->path),
                'alt' => $item->alt
            ];
        })->toArray();
    }
    public function getCategoryNameAttribute()
    {
        return $this->category?->title;
    }

    public function getProductGroupNameAttribute()
    {
        return $this->productGroup?->title;
    }

    public function getStockStatusAttribute(): string
    {
        return match (true){
            $this->qty <= 0 => 'out_of_stock',
            $this->qty <= 5 => 'low_stock',
            default => 'in_stock',
        };
    }
}

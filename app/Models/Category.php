<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'parent_id',
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

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}

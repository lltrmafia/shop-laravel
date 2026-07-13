<?php

namespace App\Services\Client;



use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{

    public static function getBreadcrumbs(Category $category): Collection
    {
        $breadcrumbs = self::getParent($category);
        return $breadcrumbs->map(function ($breadcrumb) {
            return [
                'title' => $breadcrumb->title,
                'path' => '/catalog/' . $breadcrumb->slug,
            ];
        });
    }

    private static function getParent($category): Collection
    {
        $arr = collect([$category]);
        if ($category->parent) {
            $arr = self::getParent($category->parent)->merge($arr);
        }
        return $arr;
    }
}

<?php

namespace App\Services\Client;

use Illuminate\Database\Eloquent\Collection;
use PhpParser\Node\Expr\Cast\Object_;

class ProductFilterBuilder
{
    public static function getParams(Collection $products): array
    {
        $collection = collect($products->flatMap(function ($product) {
            return $product->params->map(function ($param) {
                return [
                    'id' => $param->id,
                    'title' => $param->title,
                    'value' => $param->pivot->value,
                    'input_type' => $param->input_type->name,
                ];
            });
        }));
        $grouped = $collection->groupBy('id');

        return $finished = $grouped->map(function ($param) {
            $first = $param->first();
            return [
                'id' => $first['id'],
                'title' => $first['title'],
                'input_type' => strtolower($first['input_type']),
                'values' => $param->map(function ($value) {
                    return $value['value'];
                })->unique()->values()->all(),
            ];
        })->values()->all();
    }

    public static function applyFilters($query, $request): object
    {
        $filters = $request->query('filter');
        if($filters){
            foreach ($filters as $paramId => $param) {
                if (empty($param['value'])) {
                    continue;
                }

                $query->whereHas('params', function ($paramQuery) use ($paramId, $param) {
                    $paramQuery->where('param_id', $paramId);
                    if ($param['input_type'] === 'integer') {
                        $paramQuery->whereRaw(
                            'value::integer BETWEEN ? AND ?',
                            $param['value']
                        );
                    } else {
                        $paramQuery->whereIn('value', $param['value']);
                    }
                });
            }
        }
        return $query;
    }

    public static function applySort($query, $request)
    {
        $sorts = $request->query('sort');
        if($sorts){
            switch ($sorts) {
                case 'price_asc':
                    $query->orderBy('price');
                    break;
                case 'price_desc':
                    $query->orderByDesc('price');
                    break;
                case 'popular':
                    $query->oldest('id');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->oldest('id');
            }
        }
        return $query;
    }
}

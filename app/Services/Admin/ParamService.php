<?php

namespace App\Services\Admin;

use App\Models\Param;

class ParamService
{
    public static function store(array $data): Param
    {
        $param = Param::create($data);
        return $param;
    }
    public static function update(Param $param, array $data): Param
    {
        $param->update($data);
        return $param->fresh();
    }
    public static function restore($restoredParam): Param
    {

        $restoredParam->restore();
        return $restoredParam;
    }
    public static function forceDelete($id): void
    {
        $deletedParam = Param::withTrashed()->findOrFail($id);
        $deletedParam->forceDelete();
    }
}

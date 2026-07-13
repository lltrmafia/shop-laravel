<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Param\FilterType;
use App\Enums\Param\InputType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Param\StoreRequest;
use App\Http\Requests\Admin\Param\UpdateRequest;
use App\Http\Resources\Admin\ParamResource;
use App\Models\Param;
use App\Services\Admin\ParamService;
use Illuminate\Http\Response;

class ParamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trashedParams = ParamResource::collection(Param::onlyTrashed()->get())->resolve();
        $params = ParamResource::collection(Param::all())->resolve();
        return inertia('Admin/Param/Index', compact('params', 'trashedParams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $filterTypes = FilterType::options();
        $inputTypes = InputType::options();
        return inertia('Admin/Param/Create', compact('filterTypes',  'inputTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $param = ParamService::store($data);
        return ParamResource::make($param)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(Param $param)
    {
        $param = ParamResource::make($param)->resolve();
        return inertia('Admin/Param/Show', compact('param'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Param $param)
    {
        $filterTypes = FilterType::options();
        $param = ParamResource::make($param)->resolve();
        return inertia('Admin/Param/Edit', compact('param',  'filterTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Param $param)
    {
        $data = $request->validated();
        $param = ParamService::update($param, $data);
        return ParamResource::make($param)->resolve();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Param $param)
    {
        $param->delete();
        return response()->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }

    public function trash()
    {
        $params = ParamResource::collection(Param::all())->resolve();
        $trashedParams = ParamResource::collection(Param::onlyTrashed()->get())->resolve();
        return inertia('Admin/Param/Trash', compact('trashedParams', 'params'));
    }
    public function restore($id)
    {
        $restoredCategory = Param::withTrashed()->findOrFail($id);
        ParamService::restore($restoredCategory);
        return $restoredCategory;
    }
    public function ForceDelete($id)
    {
        ParamService::forceDelete($id);
        return response()->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }
}

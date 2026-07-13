<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaAsset\StoreRequest;
use App\Http\Requests\Admin\MediaAsset\UpdateRequest;
use App\Http\Resources\Admin\MediaAssetResource;
use App\Models\MediaAsset;
use App\Services\Admin\MediaAssetService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MediaAssetController extends Controller
{
    public function index()
    {
        $images = MediaAsset::with(['products']);
        $images = MediaAssetResource::collection($images->latest()->get())->resolve();
        return inertia('Admin/MediaAsset/Index', compact('images'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $files = $request->file('images');
        $images = MediaAssetService::store($data, $files);
        return MediaAssetResource::collection($images)->resolve();
    }

    public function update(UpdateRequest $request, MediaAsset $mediaAsset)
    {
        $data = $request->validated();
        $mediaAsset = MediaAssetService::update($mediaAsset, $data);
        return MediaAssetResource::make($mediaAsset)->resolve();
    }

    public function list()
    {
        return MediaAssetResource::collection(MediaAsset::latest()->get())->resolve();
    }

    public function destroy(MediaAsset $mediaAsset){
        $mediaAsset->products()->detach();
        Storage::disk('public')->delete($mediaAsset->path);
        $mediaAsset->delete();
        return response()->json([
            'message' => 'success'
        ], Response::HTTP_OK);
    }
}

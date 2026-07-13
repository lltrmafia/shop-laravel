<?php

namespace App\Services\Admin;

use App\Models\MediaAsset;

class MediaAssetService
{
    public static function store(array $data, array $files): array
    {
        $images = [];
        foreach ($files as $file) {
            $path = $file->store('media', 'public');
            $dimensions = getimagesize($file->getRealPath());
            $images[] = MediaAsset::create([
                'disk' => 'public',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $dimensions[0] ?? null,
                'height' => $dimensions[1] ?? null,
                'alt' => $data['alt'] ?? null,
                'created_by' => auth()->id(),
            ]);
        }
        return $images;
    }

    public static function update(MediaAsset $mediaAsset, array $data): MediaAsset
    {
        $mediaAsset->update($data);
        return $mediaAsset->fresh();
    }
}

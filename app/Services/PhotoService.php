<?php

namespace App\Services;

use App\Models\ChildPhoto;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PhotoService
{
    public function uploadPhotos(array $data, $uploaderId): array
    {
        $photos = [];
        $manager = new ImageManager(new Driver());

        foreach ($data['photos'] as $uploadedFile) {
            $photoPath = $uploadedFile->store('children/photos', 'public');

            $image = $manager->read($uploadedFile);
            $image->cover(300, 300);

            $thumbnailPath = 'children/thumbnails/' . basename($photoPath);
            Storage::disk('public')->put($thumbnailPath, (string) $image->encode());

            $photos[] = ChildPhoto::create([
                'child_id' => $data['child_id'],
                'uploaded_by' => $uploaderId,
                'photo_path' => $photoPath,
                'thumbnail_path' => $thumbnailPath,
                'activity' => $data['activity'] ?? null,
                'description' => $data['description'] ?? null,
                'photo_date' => $data['photo_date'],
                'file_size' => $uploadedFile->getSize(),
                'mime_type' => $uploadedFile->getMimeType(),
            ]);
        }

        return $photos;
    }

    public function getPhotos(array $filters = [])
    {
        $query = ChildPhoto::with(['child', 'uploader']);

        if (!empty($filters['child_id'])) {
            $query->where('child_id', $filters['child_id']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('photo_date', $filters['date']);
        }

        if (!empty($filters['activity'])) {
            $query->where('activity', 'like', '%' . $filters['activity'] . '%');
        }

        return $query->latest('photo_date')->paginate(20);
    }

    public function deletePhoto(ChildPhoto $photo): bool
    {
        Storage::disk('public')->delete($photo->photo_path);
        if ($photo->thumbnail_path) {
            Storage::disk('public')->delete($photo->thumbnail_path);
        }

        return $photo->delete();
    }
}

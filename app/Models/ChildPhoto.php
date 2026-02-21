<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ChildPhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'child_id',
        'uploaded_by',
        'photo_path',
        'thumbnail_path',
        'activity',
        'description',
        'photo_date',
        'file_size',
        'mime_type',
    ];

    protected function casts(): array
    {
        return [
            'photo_date' => 'date',
        ];
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getPhotoUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->photo_path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path
            ? Storage::disk('public')->url($this->thumbnail_path)
            : $this->photo_url;
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        return number_format($bytes / 1024, 2) . ' KB';
    }
}

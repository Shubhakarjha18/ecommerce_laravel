<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Stores the entity image in the Spatie media library ("photo" collection)
 * while keeping the legacy `photo` column working as a fallback for old rows.
 *
 * Blade views keep using `$model->photo` exactly as before: the accessor
 * resolves the media library URL first and falls back to the old value.
 */
trait HasMediaPhoto
{
    use InteractsWithMedia;

    /**
     * Media collection used to store the main image of this model.
     */
    public function getMediaPhotoCollection(): string
    {
        return 'photo';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->getMediaPhotoCollection())->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->nonQueued();
    }

    /**
     * Public URL of the image: media library first, legacy column as fallback.
     */
    public function getPhotoAttribute(): string
    {
        $url = $this->getFirstMediaUrl($this->getMediaPhotoCollection());
        if ($url) {
            return $url;
        }

        $legacy = (string)($this->attributes['photo'] ?? '');
        if ($legacy === '') {
            return '';
        }

        // Old rows may hold bare paths (photos/1/banner-01.jpg), /storage/... links or full URLs.
        if (Str::startsWith($legacy, ['http://', 'https://', '//'])) {
            return $legacy;
        }

        return asset(ltrim($legacy, '/'));
    }
}

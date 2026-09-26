<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMediaPhoto;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Settings extends Model implements HasMedia
{
    use HasMediaPhoto;

    protected $fillable=['short_des','description','photo','address','phone','email','logo'];

    /** Images are stored in the media library; the columns only keep legacy data. */
    protected $attributes=['photo'=>'','logo'=>''];

    /**
     * Settings has two images: the store logo and the about photo.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection($this->getMediaPhotoCollection())->singleFile();
        $this->addMediaCollection('logo')->singleFile();
    }

    /**
     * Public URL of the store logo: media library first, legacy column as fallback.
     */
    public function getLogoAttribute(): string
    {
        if ($url = $this->getFirstMediaUrl('logo')) {
            return $url;
        }

        $legacy = (string)($this->attributes['logo'] ?? '');
        if ($legacy === '') {
            return '';
        }

        if (Str::startsWith($legacy, ['http://', 'https://', '//'])) {
            return $legacy;
        }

        return asset(ltrim($legacy, '/'));
    }
}

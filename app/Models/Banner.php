<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMediaPhoto;
use Spatie\MediaLibrary\HasMedia;

class Banner extends Model implements HasMedia
{
    use HasMediaPhoto;

    protected $fillable=['title','slug','description','photo','status'];

    /** The image itself is stored in the media library; the column only keeps legacy data. */
    protected $attributes=['photo'=>''];
}

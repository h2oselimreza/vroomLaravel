<?php

namespace App\Models\Web;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebGalleryAlbum extends Model
{
    use HasFactory, TracksUser;

    protected $table = 'web_gallery_album';

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';
    

    protected $fillable = [
        'album_name',
        'album_order',
        'is_active',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];

    protected $casts = [
        'album_order' => 'integer',
        'is_active' => 'boolean',
        'created_dt_tm' => 'datetime',
        'updated_dt_tm' => 'datetime',
    ];

    public function images()
    {
        return $this->hasMany(WebGalleryImage::class, 'gallery_album');
    }
}

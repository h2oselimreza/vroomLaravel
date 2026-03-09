<?php

namespace App\Models\Web;

use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebGalleryImage extends Model
{
    use HasFactory, TracksUser;
    protected $table = 'web_gallery_image';

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';


    protected $fillable = [
        'gallery_album',
        'image',
        'is_active',
        'home_flag',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (Optional)
    |--------------------------------------------------------------------------
    */

    public function album()
    {
        return $this->belongsTo(WebGalleryAlbum::class, 'gallery_album');
    }

    public static function getGalleryAlbumImage($albumId = null, $homeFlag = 0)
    {
        return self::when($albumId, function ($q) use ($albumId) {
                    $q->where('gallery_album', $albumId);
                })
                ->when($homeFlag, function ($q) {
                    $q->where('home_flag', 1);
                })
                ->where('is_active', 1)
                ->get()
                ->toArray();
    }
}

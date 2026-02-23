<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Road extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id',
        'road_code',
        'road_name',
        'road_order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->user()->user_id ?? 'system';
            $model->updated_by = auth()->user()->user_id ?? 'system';
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->user_id ?? 'system';
        });

        static::created(function ($group) {

            $text = \DB::table('token')
                ->where('code', 'RD-')
                ->first();

            $prefix = $text->code ?? 'RD-';

            $group->road_code =
                $prefix . str_pad($group->id, 5, '0', STR_PAD_LEFT);

            $group->saveQuietly(); // prevents loop
        });
    }

    public static function getSocietyRoad()
    {
        return Road::where('is_active', 1)->get();
    }

}

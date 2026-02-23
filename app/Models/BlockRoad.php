<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockRoad extends Model
{
    protected $fillable = [
        'block',
        'road',
        'status',
        'created_by',
        'created_dt_tm',
        'updated_by',
        'updated_dt_tm',
    ];

    public $timestamps = false;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user()->user_id ?? 'system';

            $model->created_by = $user;
            $model->updated_by = $user;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->user_id ?? 'system';
        });
    }

    public function getBlockRoads()
    {
        return Block::with(['roads' => function ($query) {
            $query->where('block_roads.status', 1);
        }])->where('is_active', 1)->get();
    }
}

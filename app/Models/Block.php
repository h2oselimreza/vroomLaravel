<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Block extends Model
{
     protected $fillable = [
        'block_code',
        'block_name',
        'block_order',
        'is_active',
        'created_by',
        'updated_by',
        'created_dt_tm',
        'updated_dt_tm',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

        static::created(function ($group) {

            $prefix = \DB::table('token')
                        ->where('code', 'BLK-')
                        ->value('code') ?? 'BLK-';

            $group->block_code =
                $prefix . str_pad($group->id, 5, '0', STR_PAD_LEFT);

            $group->saveQuietly();
        });
    }

    public static function getSocietyBlock()
    {
        return Block::where('is_active', 1)->get();
    }

    public static function getSocietyBlocksRoads()
    {
        return DB::table('block_roads')
            ->select(
                'block_roads.block',
                'block_roads.road',
                'blocks.block_name',
                'roads.road_name'
            )
            ->join('blocks', 'blocks.block_code', '=', 'block_roads.block')
            ->join('roads', 'roads.road_code', '=', 'block_roads.road')
            ->where('block_roads.status', 1)
            ->get();
    }
}

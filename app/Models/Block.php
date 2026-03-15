<?php

namespace App\Models;

use App\Services\TokenService;
use App\Traits\TracksUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Block extends Model
{
    use HasFactory, TracksUser;
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

    const CREATED_AT = 'created_dt_tm';
    const UPDATED_AT = 'updated_dt_tm';

    protected static function boot()
    {
        parent::boot();

        static::created(function ($group) {

            $prefix = 'M-GRP-';

            $tokenService = app(TokenService::class);

            $block_code = $prefix . $tokenService->getTokenByCode($prefix);

            $group->block_code = $block_code;
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

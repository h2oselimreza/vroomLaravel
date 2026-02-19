<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModuleGroup extends Model
{
    use HasFactory;

    protected $table = 'module_group'; // because model name differs from table

    protected $primaryKey = 'id';

    public $timestamps = false; 
    // set false because your table uses created_dt_tm & updated_dt_tm

    protected $fillable = [
        'module_group_name',
        'module_group_code',
        'module_group_order',
        'panel_type',
        'created_by',
        'updated_by', 
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {

            $nextId = \DB::table('module_group')->max('id') + 1;

            $group->module_group_code =
                'M-GRP-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'module_group', 'module_group_code')
                    ->orderBy('module_order');
    }
}

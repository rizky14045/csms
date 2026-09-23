<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'parent_unit_id', 'id');
    }

    public function uls()
    {
        return $this->hasMany(Unit::class, 'parent_unit_id', 'id')->where('type', 'UL');
    }
}

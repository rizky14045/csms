<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fasum extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'fasum';
    protected $guarded = ['id'];


    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

}

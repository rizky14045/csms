<?php

namespace App\Models;

use App\Models\MarturityLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarturitySubArea extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function levels()
    {
        return $this->hasMany(MarturityLevel::class, 'sub_area_id', 'id');
    }
}

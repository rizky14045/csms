<?php

namespace App\Models;

use App\Models\MarturitySubArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarturityArea extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function subAreas()
    {
        return $this->hasMany(MarturitySubArea::class, 'area_id', 'id')->orderBy('id', 'asc');
    }
}

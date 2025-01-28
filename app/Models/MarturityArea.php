<?php

namespace App\Models;

use App\Models\MarturitySubArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarturityArea extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function subAreas()
    {
        return $this->hasMany(MarturitySubArea::class, 'area_id', 'id');
    }
}

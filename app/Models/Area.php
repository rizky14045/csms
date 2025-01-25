<?php

namespace App\Models;

use App\Models\SubArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function subAreas()
    {
        return $this->hasMany(SubArea::class, 'area_id', 'id');
    }
}

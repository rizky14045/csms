<?php

namespace App\Models;

use App\Models\KpiSubArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiArea extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function subAreas()
    {
        return $this->hasMany(KpiSubArea::class, 'area_id', 'id');
    }
}

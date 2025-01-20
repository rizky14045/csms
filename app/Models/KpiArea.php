<?php

namespace App\Models;

use App\Models\KpiSubArea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiArea extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function subAreas()
    {
        return $this->hasMany(KpiSubArea::class, 'kpi_area_id', 'id');
    }
}

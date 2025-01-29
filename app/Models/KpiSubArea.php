<?php

namespace App\Models;

use App\Models\KpiLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiSubArea extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function levels()
    {
        return $this->hasMany(KpiLevel::class, 'sub_area_id', 'id');
    }
}

<?php

namespace App\Models;

use App\Models\KpiLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiSubArea extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function levels()
    {
        return $this->hasMany(KpiLevel::class, 'sub_area_id', 'id');
    }
}

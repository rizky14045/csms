<?php

namespace App\Models;

use App\Models\MarturityLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarturitySubArea extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function levels()
    {
        return $this->hasMany(MarturityLevel::class, 'marturity_sub_area_id', 'id');
    }
}

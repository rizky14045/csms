<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'kpis';

    public function unit()
    {
        return $this->hasOne(User::class, 'id', 'unit_id');
    }

}

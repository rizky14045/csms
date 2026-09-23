<?php

namespace App\Models;

use App\Models\Level;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubArea extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];

    public function levels()
    {
        return $this->hasMany(Level::class, 'sub_area_id', 'id')->orderBy('order');
    }
}

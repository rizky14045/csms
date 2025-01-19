<?php

namespace App\Models;

use App\Models\LevelAssesment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SignLevelAssesment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function level()
    {
        return $this->hasOne(LevelAssesment::class, 'level_assesment_id', 'id');
    }
}

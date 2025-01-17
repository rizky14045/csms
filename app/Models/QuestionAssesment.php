<?php

namespace App\Models;

use App\Models\LevelAssesment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionAssesment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function levels()
    {
        return $this->hasMany(LevelAssesment::class, 'question_id', 'id');
    }
}

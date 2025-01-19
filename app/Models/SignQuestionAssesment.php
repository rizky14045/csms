<?php

namespace App\Models;

use App\Models\QuestionAssesment;
use App\Models\SignLevelAssesment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SignQuestionAssesment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function question()
    {
        return $this->hasOne(QuestionAssesment::class, 'question_assesment_id', 'id');
    }

    public function levels()
    {
        return $this->hasMany(SignLevelAssesment::class, 'sign_question_id', 'id');
    }
}

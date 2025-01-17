<?php

namespace App\Models;

use App\Models\QuestionAssesment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryAssesment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function questions()
    {
        return $this->hasMany(QuestionAssesment::class, 'category_id', 'id');
    }
}

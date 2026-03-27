<?php

namespace App\Models;

use App\Models\CategoryAssesment;
use App\Models\SignQuestionAssesment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SignCategoryAssesment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->hasOne(CategoryAssesment::class, 'id', 'category_assesment_id');
    }

    public function questions()
    {
        return $this->hasMany(SignQuestionAssesment::class, 'sign_category_id', 'id');
    }
}

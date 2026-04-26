<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FasumType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fasum_types';
    protected $guarded = ['id'];
    
}

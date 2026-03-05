<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BujpProfile extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'bujp_profiles';
    protected $guarded = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function programs()
    {
        return $this->hasMany(MainSecurityProgram::class, 'program_id', 'id');
    }
}

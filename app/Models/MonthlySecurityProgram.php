<?php

namespace App\Models;

use App\Models\SecurityProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySecurityProgram extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function securityProgram()
    {
        return $this->hasOne(SecurityProgram::class, 'id', 'program_id');
    }

    public function programs()
    {
        return $this->hasMany(MonthlyMainSecurityProgram::class, 'monthly_program_id', 'id');
    }
}

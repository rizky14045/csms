<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyMainSecurityProgram extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function mainProgram()
    {
        return $this->hasOne(MainSecurityProgram::class, 'id', 'main_program_id');
    }

}

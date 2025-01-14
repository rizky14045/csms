<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyReport extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function unit()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

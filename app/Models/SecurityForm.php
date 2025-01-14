<?php

namespace App\Models;

use App\Models\Security;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SecurityForm extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function security()
    {
        return $this->hasOne(Security::class, 'id', 'security_id');
    }
}

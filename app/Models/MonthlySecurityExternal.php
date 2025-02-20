<?php

namespace App\Models;

use App\Models\SecurityExternal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySecurityExternal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function security()
    {
        return $this->hasOne(SecurityExternal::class, 'id', 'security_external_id')->withTrashed();
    }
}

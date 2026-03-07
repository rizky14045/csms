<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditSMP extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'audit_smp';
    protected $guarded = ['id'];

    public function kriteria()
    {
        return $this->hasMany(AuditSMP::class, 'parent_id')->where('type', 'kriteria');
    }

    public function evident()
    {
        return $this->hasMany(AuditSMP::class, 'parent_id')->where('type', 'evident');
    }
}

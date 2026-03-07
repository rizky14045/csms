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


    public function pernyataan()
    {
        return $this->hasMany(AuditSMP::class, 'parent_id')
            ->where('type', 'pernyataan')
            ->orderBy('created_at', 'asc');
    }
    public function kriteria()
    {
        return $this->hasMany(AuditSMP::class, 'parent_id')
            ->where('type', 'kriteria')
            ->orderBy('created_at', 'asc');
    }

    public function evident()
    {
        return $this->hasMany(AuditSMP::class, 'parent_id')
            ->where('type', 'evident')
            ->orderBy('created_at', 'asc');
    }
}

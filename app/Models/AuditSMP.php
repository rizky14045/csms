<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditSMP extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'audit_smp';

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(AuditSMP::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AuditSMP::class, 'parent_id')
            ->orderBy('created_at', 'asc');
    }

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

    public function evidence()
    {
        return $this->hasMany(AuditSMP::class, 'parent_id')
            ->where('type', 'evidence')
            ->orderBy('created_at', 'asc');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeHeader($query)
    {
        return $query->where('type', 'header');
    }

    public function scopePernyataan($query)
    {
        return $query->where('type', 'pernyataan');
    }

    public function scopeKriteria($query)
    {
        return $query->where('type', 'kriteria');
    }

}

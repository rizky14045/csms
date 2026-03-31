<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditSMPScore extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'audit_smp_score';
    protected $guarded = ['id'];

    public function auditData()
    {
        return $this->belongsTo(AuditSmpData::class, 'audit_smp_data_id');
    }
    
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function parent()
    {
        return $this->belongsTo(AuditSMPScore::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(AuditSMPScore::class, 'parent_id')
            ->orderBy('created_at', 'asc');
    }

    public function pernyataan()
    {
        return $this->hasMany(AuditSMPScore::class, 'parent_id')
            ->where('type', 'pernyataan')
            ->orderBy('created_at', 'asc');
    }

    public function kriteria()
    {
        return $this->hasMany(AuditSMPScore::class, 'parent_id')
            ->where('type', 'kriteria')
            ->orderBy('created_at', 'asc');
    }

    public function evidence()
    {
        return $this->hasMany(AuditSMPScore::class, 'parent_id')
            ->where('type', 'evidence')
            ->orderBy('created_at', 'asc');
    }

}

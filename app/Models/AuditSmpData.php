<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditSmpData extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function children($type = null)
     {
         $query = $this->hasMany(AuditSMPScore::class, 'audit_smp_data_id')
             ->orderBy('created_at', 'asc');

         if ($type) {
             $query->where('type', $type);
         }

         return $query;
     }

     public function childrenHeader()
    {
        return $this->hasMany(AuditSMPScore::class, 'audit_smp_data_id')
            ->where('type', 'header')
            ->orderBy('created_at', 'asc');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function leadAuditor()
    {
        return $this->belongsTo(User::class, 'auditor_lead_id');
    }

    public function auditors()
    {
        return $this->belongsToMany(
            User::class,
            'auditors',          // pivot table
            'audit_smp_data_id', // FK to this model in pivot table
            'user_id'            // FK to users table
        )->where('auditors.deleted_at', null); // Exclude soft-deleted auditors
    }

    public function getInvalidItemsEvidenceByUnit()
    {
        return $this->hasMany(
            \App\Models\AuditSMPScore::class,
            'audit_smp_data_id'
        )->where(function ($q) {
            $q->whereNull('evidence_file')
            ->orWhere('pencapaian_nilai_kriteria_self', 0)
            ->orWhere('pencapaian_nilai_kriteria_self', null);
        })->where('type', '=', 'evidence');
    }

    public function getInvalidItemsEvidenceByAuditor()
    {
        return $this->hasMany(
            \App\Models\AuditSMPScore::class,
            'audit_smp_data_id'
        )->where(function ($q) {
            $q
            ->where('pencapaian_nilai_kriteria', 0)
            ->orWhere('pencapaian_nilai_kriteria', null);
        })->where('type', '=', 'evidence');
    }
}

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

    public function kriteria()
    {
        return $this->hasMany(AuditSMPScore::class, 'parent_id')->where('type', 'kriteria');
    }

    public function element()
    {
        return $this->hasMany(AuditSMPScore::class, 'parent_id')->where('type', 'element');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecurityProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted()
    {
        // Master (user_id terisi) berubah/dihapus: laporan lama yang menunjuk langsung ke master dibekukan dulu.
        static::updating(function ($model) {
            if ($model->user_id !== null && $model->isDirty(['program_name', 'description', 'year'])) {
                \App\Services\MonthlyReport\ProgramReportSnapshot::detachProgramRows($model, $model->getOriginal());
            }
        });

        static::deleting(function ($model) {
            if ($model->user_id !== null) {
                \App\Services\MonthlyReport\ProgramReportSnapshot::detachProgramRows($model, $model->getOriginal());
            }
        });
    }

    public function programs()
    {
        return $this->hasMany(MainSecurityProgram::class, 'program_id', 'id');
    }
}

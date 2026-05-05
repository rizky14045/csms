<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kpi extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'kpis';

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function getInvalidItemsNotesByUnit()
    {
        return $this->hasMany(
            \App\Models\KpiNote::class,
            'kpi_id'
        )->where(function ($q) {
            $q->whereNull('attachment_file');
        });
    }

}

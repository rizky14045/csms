<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marturity extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'send_date' => 'date',
    ];

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function getInvalidItemsNotesByUnit()
    {
        return $this->hasMany(
            \App\Models\MarturityNote::class,
            'marturity_id'
        )->where(function ($q) {
            $q->whereNull('attachment_file');
        });
    }

}

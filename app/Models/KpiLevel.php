<?php

namespace App\Models;

use App\Models\KpiNote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiLevel extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function notes()
    {
        return $this->hasMany(KpiNote::class, 'level_id', 'id')->orderBy('id', 'asc');
    }
}

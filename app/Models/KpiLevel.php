<?php

namespace App\Models;

use App\Models\KpiNote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KpiLevel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function notes()
    {
        return $this->hasMany(KpiNote::class, 'level_id', 'id');
    }
}

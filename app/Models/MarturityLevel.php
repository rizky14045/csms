<?php

namespace App\Models;

use App\Models\MarturityNote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarturityLevel extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function notes()
    {
        return $this->hasMany(MarturityNote::class, 'level_id', 'id')->orderBy('id', 'asc');
    }
}

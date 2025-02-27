<?php

namespace App\Models;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];

    public function notes()
    {
        return $this->hasMany(Note::class, 'level_id', 'id');
    }
}

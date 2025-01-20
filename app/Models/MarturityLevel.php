<?php

namespace App\Models;

use App\Models\MarturityNote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarturityLevel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function notes()
    {
        return $this->hasMany(MarturityNote::class, 'marturity_level_id', 'id');
    }
}

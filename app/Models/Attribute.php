<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];

    public function children()
    {
        return $this->hasMany(Attribute::class, 'parent_attribute_id');
    }
}

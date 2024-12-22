<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model implements Authenticatable
{
    use HasFactory,SoftDeletes,AuthenticableTrait;
    protected $guarded = ['id'];
}

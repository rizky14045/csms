<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Vendor extends Model implements Authenticatable
{
    use HasFactory,SoftDeletes,AuthenticableTrait;
    protected $guarded = ['id'];
}

<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assesment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }
    public function unit()
    {
        return $this->hasOne(User::class, 'id', 'unit_id');
    }



}

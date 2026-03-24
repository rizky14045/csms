<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assesment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }
    public function unit()
    {
        return $this->hasOne(User::class, 'id', 'unit_id');
    }
    public function bujpProfile()
    {
        return $this->hasOne(BujpProfile::class, 'user_id', 'created_by');
    }


}

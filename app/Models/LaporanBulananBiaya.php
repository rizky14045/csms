<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanBulananBiaya extends Model
{
    use HasFactory;

    protected $table = 'laporan_bulanan_biaya';
    protected $guarded = ['id'];
}

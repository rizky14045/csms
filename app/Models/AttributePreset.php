<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Daftar nama attribute yang sudah ditentukan (predefined) sebagai pilihan
 * saat unit membuat master data attribute. Nama yang diketik sendiri oleh unit
 * hanya disimpan di attribute unit tersebut, tidak masuk ke tabel ini.
 */
class AttributePreset extends Model
{
    protected $guarded = ['id'];
}

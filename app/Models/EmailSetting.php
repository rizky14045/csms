<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailSetting extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    /**
     * Map the stored/submitted "security" value (ssl/tls/none)
     * to the encryption value expected by mail.mailers.smtp.encryption.
     */
    public static function encryptionFromSecurity($security)
    {
        $security = strtolower((string) $security);

        return in_array($security, ['ssl', 'tls'], true) ? $security : null;
    }
}

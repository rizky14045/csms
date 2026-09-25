<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExternalAuditorAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $auditorName;
    public $email;
    public $password;
    public $expiredAt;
    public $unitName;
    public $audits;
    public $isReset;

    /** @param array $audits daftar teks audit yang boleh diakses */
    public function __construct($auditorName, $email, $password, $expiredAt, $unitName, array $audits, bool $isReset = false)
    {
        $this->auditorName = $auditorName;
        $this->email = $email;
        $this->password = $password;
        $this->expiredAt = $expiredAt;
        $this->unitName = $unitName;
        $this->audits = $audits;
        $this->isReset = $isReset;
    }

    public function build()
    {
        return $this
            ->subject($this->isReset ? 'Akses Baru Auditor External SIdak Anda' : 'Akun Auditor External SIdak Anda')
            ->view('emails.auditor.external-account');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecurityCardExpiringSoon extends Mailable
{
    use Queueable, SerializesModels;

    public $memberName;
    public $unitWork;
    public $registrationNumber;
    public $expiredDate;

    public function __construct($memberName, $unitWork, $registrationNumber, $expiredDate)
    {
        $this->memberName = $memberName;
        $this->unitWork = $unitWork;
        $this->registrationNumber = $registrationNumber;
        $this->expiredDate = $expiredDate;
    }

    public function build()
    {
        return $this
            ->subject('Peringatan: KTA Satpam Akan Berakhir - SIdak')
            ->view('emails.security.card-expiring-soon');
    }
}

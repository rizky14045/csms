<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorName;
    public $email;
    public $password;
    public $contractNumber;
    public $startDate;
    public $endDate;

    public function __construct($vendorName, $email, $password, $contractNumber, $startDate, $endDate)
    {
        $this->vendorName = $vendorName;
        $this->email = $email;
        $this->password = $password;
        $this->contractNumber = $contractNumber;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function build()
    {
        return $this
            ->subject('Akun BUJP / Vendor SIdak Anda')
            ->view('emails.vendor.account-created');
    }
}

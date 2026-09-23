<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorContractCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorName;
    public $contractNumber;
    public $startDate;
    public $endDate;

    public function __construct($vendorName, $contractNumber, $startDate, $endDate)
    {
        $this->vendorName = $vendorName;
        $this->contractNumber = $contractNumber;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function build()
    {
        return $this
            ->subject('Kontrak Baru BUJP / Vendor CSMS')
            ->view('emails.vendor.contract-created');
    }
}

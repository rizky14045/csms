<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorContractExpiringSoon extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorName;
    public $contractNumber;
    public $endDate;

    public function __construct($vendorName, $contractNumber, $endDate)
    {
        $this->vendorName = $vendorName;
        $this->contractNumber = $contractNumber;
        $this->endDate = $endDate;
    }

    public function build()
    {
        return $this
            ->subject('Peringatan: Kontrak BUJP / Vendor Akan Berakhir - CSMS')
            ->view('emails.vendor.contract-expiring-soon');
    }
}

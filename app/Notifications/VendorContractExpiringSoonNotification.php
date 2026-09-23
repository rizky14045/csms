<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class VendorContractExpiringSoonNotification extends Notification
{
    protected $vendor;

    public function __construct($vendor)
    {
        $this->vendor = $vendor;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'category' => 'vendor_contract_expiring',
            'title' => 'Kontrak BUJP / Vendor Akan Berakhir',
            'message' => "Kontrak BUJP / Vendor {$this->vendor->name} ({$this->vendor->contract_number}) akan berakhir pada " .
                \Carbon\Carbon::parse($this->vendor->end_date)->format('d-m-Y') .
                ". Segera lakukan perpanjangan kontrak atau siapkan pengadaan vendor baru.",
            'vendor_id' => $this->vendor->id,
            'vendor_name' => $this->vendor->name,
            'contract_number' => $this->vendor->contract_number,
            'end_date' => $this->vendor->end_date,
            'url' => route('user.vendor.index'),
        ];
    }
}

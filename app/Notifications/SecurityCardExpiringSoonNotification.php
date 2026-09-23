<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SecurityCardExpiringSoonNotification extends Notification
{
    protected $security;

    public function __construct($security)
    {
        $this->security = $security;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'category' => 'security_card_expiring',
            'title' => 'KTA Akan Berakhir',
            'message' => "KTA atas nama {$this->security->name} ({$this->security->registration_number}) akan berakhir pada " .
                \Carbon\Carbon::parse($this->security->expired_card_date)->format('d-m-Y') .
                ". Segera lakukan perpanjangan KTA anggota tersebut.",
            'security_id' => $this->security->id,
            'member_name' => $this->security->name,
            'registration_number' => $this->security->registration_number,
            'expired_card_date' => $this->security->expired_card_date,
            'url' => route('user.security.edit', ['security' => $this->security->id]),
        ];
    }
}

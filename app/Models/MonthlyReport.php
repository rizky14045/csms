<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyReport extends Model
{
    use HasFactory;
    use \App\Models\Concerns\HasStatusHistory;
    protected $guarded = ['id'];

    public function unit()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function detailUnit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function statusHistoryCreatedLabel(): string
    {
        return 'Laporan bulanan dibuat';
    }

    public function statusHistoryChanges(): array
    {
        $events = [];

        if ($this->wasChanged('sent_to_parent')) {
            $events[] = ['sent_to_parent', $this->sent_to_parent ? 'Dikirim ke unit induk' : 'Pengiriman ke unit induk dibatalkan', null, $this->sent_to_parent ? 1 : 0];
        }

        if ($this->wasChanged('send_status')) {
            $events[] = ['send_status', $this->send_status ? 'Laporan dikirim' : 'Pengiriman laporan dibatalkan', null, $this->send_status ? 1 : 0];
        }

        return $events;
    }
}

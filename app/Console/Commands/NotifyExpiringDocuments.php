<?php

namespace App\Console\Commands;

use App\Mail\SecurityCardExpiringSoon;
use App\Mail\VendorContractExpiringSoon;
use App\Models\MonthlyReport;
use App\Models\Security;
use App\Models\SecurityForm;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\SecurityCardExpiringSoonNotification;
use App\Notifications\VendorContractExpiringSoonNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyExpiringDocuments extends Command
{
    protected $signature = 'notify:expiring-documents';

    protected $description = 'Kirim notifikasi email untuk KTA satpam yang akan expired (H-3 bulan) dan kontrak BUJP/Vendor yang akan berakhir (H-6 bulan)';

    public function handle()
    {
        $this->notifyExpiringSecurityCards();
        $this->notifyExpiringVendorContracts();

        return self::SUCCESS;
    }

    protected function notifyExpiringSecurityCards()
    {
        $securities = Security::whereNotNull('expired_card_date')
            ->whereRaw("expired_card_date - INTERVAL '3 months' = CURRENT_DATE")
            ->get();

        foreach ($securities as $security) {
            $recipient = $this->resolveSecurityRecipient($security);

            if (!$recipient) {
                Log::warning('Tidak menemukan penerima notifikasi KTA expiring', [
                    'security_id' => $security->id,
                    'name' => $security->name,
                ]);
                continue;
            }

            try {
                $recipient->notify(new SecurityCardExpiringSoonNotification($security));
                $this->info("Notifikasi (in-app) KTA expiring dibuat untuk user #{$recipient->id} (security_id: {$security->id})");
            } catch (\Throwable $th) {
                Log::error('Gagal membuat notifikasi in-app KTA expiring: ' . $th->getMessage(), [
                    'security_id' => $security->id,
                ]);
            }

            if (!$recipient->mail_address) {
                continue;
            }

            try {
                Mail::to($recipient->mail_address)->send(new SecurityCardExpiringSoon(
                    $security->name,
                    $security->unit_work,
                    $security->registration_number,
                    $security->expired_card_date
                ));

                $this->info("Notifikasi email KTA expiring terkirim ke {$recipient->mail_address} (security_id: {$security->id})");
            } catch (\Throwable $th) {
                Log::error('Gagal mengirim notifikasi KTA expiring: ' . $th->getMessage(), [
                    'security_id' => $security->id,
                ]);
            }
        }
    }

    protected function resolveSecurityRecipient(Security $security)
    {
        if ($security->user_id) {
            return User::find($security->user_id);
        }

        $form = SecurityForm::where('security_id', $security->id)->first();
        if (!$form) {
            return null;
        }

        $monthlyReport = MonthlyReport::find($form->monthly_report_id);
        if (!$monthlyReport || !$monthlyReport->user_id) {
            return null;
        }

        return User::find($monthlyReport->user_id);
    }

    protected function notifyExpiringVendorContracts()
    {
        $vendors = Vendor::whereNotNull('end_date')
            ->whereRaw("end_date - INTERVAL '6 months' = CURRENT_DATE")
            ->get();

        foreach ($vendors as $vendor) {
            $recipients = $this->resolveVendorRecipients($vendor);

            if ($recipients->isEmpty()) {
                Log::warning('Tidak menemukan penerima notifikasi kontrak vendor expiring', [
                    'vendor_id' => $vendor->id,
                    'name' => $vendor->name,
                ]);
                continue;
            }

            foreach ($recipients as $recipient) {
                try {
                    $recipient->notify(new VendorContractExpiringSoonNotification($vendor));
                    $this->info("Notifikasi (in-app) kontrak vendor expiring dibuat untuk user #{$recipient->id} (vendor_id: {$vendor->id})");
                } catch (\Throwable $th) {
                    Log::error('Gagal membuat notifikasi in-app kontrak vendor expiring: ' . $th->getMessage(), [
                        'vendor_id' => $vendor->id,
                    ]);
                }

                if (!$recipient->mail_address) {
                    continue;
                }

                try {
                    Mail::to($recipient->mail_address)->send(new VendorContractExpiringSoon(
                        $vendor->name,
                        $vendor->contract_number,
                        $vendor->end_date
                    ));

                    $this->info("Notifikasi email kontrak vendor expiring terkirim ke {$recipient->mail_address} (vendor_id: {$vendor->id})");
                } catch (\Throwable $th) {
                    Log::error('Gagal mengirim notifikasi kontrak vendor expiring: ' . $th->getMessage(), [
                        'vendor_id' => $vendor->id,
                    ]);
                }
            }
        }
    }

    protected function resolveVendorRecipients(Vendor $vendor)
    {
        if ($vendor->unit_id) {
            $unitUsers = User::where('unit_id', $vendor->unit_id)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Unit');
                })
                ->get();

            if ($unitUsers->isNotEmpty()) {
                return $unitUsers;
            }
        }

        if ($vendor->parent_user_id) {
            $parent = User::find($vendor->parent_user_id);
            if ($parent) {
                return collect([$parent]);
            }
        }

        return collect();
    }
}

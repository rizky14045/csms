<?php

namespace App\Services\EmailSetting;

use App\Helpers\JsonResponse;
use App\Models\EmailSetting;
use App\Models\Fasum;
use App\Models\FasumType;
use App\Services\ActivityLog\ActivityLogService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailSettingService
{
    protected $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }
    
   public function getEmailSettings()
    {
        try {
            $query = EmailSetting::first();
            $data = $query ? $query->toArray() : null;

            return JsonResponse::success($data, '    found', 200);

        } catch (Exception $e) {

            $this->logService->log(
                'email_setting.fetch_all',
                'Failed to fetch email settings',
                500,
                [
                    'error' => $e->getMessage(),
                    'params' => request()->all(),
                ]
            );

            return JsonResponse::error(
                $e->getMessage(),
                'Email settings not found',
                500
            );
        }
    }

    public function updateEmailSetting(array $data)
    {
        
        try {
            DB::beginTransaction();
            $emailSetting = EmailSetting::first();

            if (!$emailSetting) {
                $before = null;
                $createData = [
                    'provider' => $data['provider'] ?? null,
                    'host' => $data['host'] ?? null,
                    'port' => $data['port'] ?? null,
                    'username' => $data['username'] ?? null,
                    'password' => $data['password'] ?? null,
                    'from' => $data['from'] ?? null,
                    'alias' => $data['alias'] ?? null,
                    'timeout' => $data['timeout'] ?? null,
                    'security' => $data['security'] ?? null,
                    'email_test' => $data['email_test'] ?? null,
                    'created_by' => auth()->id(),
                ];
                $emailSetting = EmailSetting::create($createData);
            } else {
                $before = $emailSetting->toArray();

                $updateData = [
                    'provider' => $data['provider'] ?? $emailSetting->provider,
                    'host' => $data['host'] ?? $emailSetting->host,
                    'port' => $data['port'] ?? $emailSetting->port,
                    'username' => $data['username'] ?? $emailSetting->username,
                    'password' => $data['password'] ?? $emailSetting->password,
                    'from' => $data['from'] ?? $emailSetting->from,
                    'alias' => $data['alias'] ?? $emailSetting->alias,
                    'timeout' => $data['timeout'] ?? $emailSetting->timeout,
                    'security' => $data['security'] ?? $emailSetting->security, 
                    'email_test' => $data['email_test'] ?? $emailSetting->email_test,
                    'updated_by' => auth()->id(),
                ];

                $emailSetting->update($updateData);
                $this->logService->log(
                    'email_setting.update',
                    'Update email setting',
                    200,
                    [
                        'before' => $before,
                        'after'  => $emailSetting->toArray(),
                    ]
                );
            }

            DB::commit();

            Cache::forget('app.email_setting');

            $this->logService->log(
                'email_setting.update',
                'Update email setting',
                200,
                [
                    'before' => $before,
                    'after'  => $emailSetting->toArray(),
                ]
            );

            return JsonResponse::success(
                $emailSetting,
                'Email setting updated',
                201
            );

        } catch (Exception $e) {
            DB::rollBack();

            $this->logService->log(
                'email_setting.update',
                'Failed to update email setting',
                500,
                [
                    'error' => $e->getMessage(),
                    'payload' => $data,
                ]
            );

            throw $e;
        }
    }

    /**
     * Send a test email using the given (saved or unsaved) SMTP settings.
     */
    public function sendTestEmail(array $data)
    {
        Config::set([
            'mail.mailers.smtp.host' => $data['host'],
            'mail.mailers.smtp.port' => (int) $data['port'],
            'mail.mailers.smtp.username' => $data['username'],
            'mail.mailers.smtp.password' => $data['password'],
            'mail.mailers.smtp.encryption' => EmailSetting::encryptionFromSecurity($data['security']),
            'mail.mailers.smtp.timeout' => $data['timeout'] ? (int) $data['timeout'] : null,
            'mail.from.address' => $data['from'],
            'mail.from.name' => $data['alias'],
        ]);

        try {
            Mail::raw(
                "Ini adalah email test dari pengaturan Email Setting CSMS.\nJika Anda menerima email ini, konfigurasi SMTP sudah benar.",
                function ($message) use ($data) {
                    $message->to($data['email_test'])->subject('Test Email - CSMS');
                }
            );

            $this->logService->log(
                'email_setting.test',
                'Test email sent',
                200,
                ['to' => $data['email_test']]
            );

            return JsonResponse::success(null, 'Email test berhasil dikirim', 200);
        } catch (Throwable $e) {
            $this->logService->log(
                'email_setting.test',
                'Failed to send test email',
                500,
                ['error' => $e->getMessage(), 'to' => $data['email_test']]
            );

            return JsonResponse::error($e->getMessage(), 'Gagal mengirim email test', 500);
        }
    }
}

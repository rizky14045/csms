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
use Illuminate\Support\Facades\DB;

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
}

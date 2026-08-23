<?php

namespace App\Providers;

use App\Models\EmailSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        $this->applyEmailSettings();
    }

    /**
     * Override the mail config with settings stored via the
     * admin "Email Setting" page, so it drives outgoing mail
     * instead of the .env MAIL_* values.
     */
    protected function applyEmailSettings()
    {
        try {
            $emailSetting = Cache::remember('app.email_setting', 300, function () {
                return EmailSetting::first();
            });
        } catch (\Throwable $e) {
            // DB/table not ready yet (fresh install, migrations running, etc.)
            return;
        }

        if (!$emailSetting) {
            return;
        }

        $encryption = EmailSetting::encryptionFromSecurity($emailSetting->security);

        Config::set([
            'mail.mailers.smtp.host' => $emailSetting->host,
            'mail.mailers.smtp.port' => (int) $emailSetting->port,
            'mail.mailers.smtp.username' => $emailSetting->username,
            'mail.mailers.smtp.password' => $emailSetting->password,
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.mailers.smtp.timeout' => $emailSetting->timeout ? (int) $emailSetting->timeout : null,
            'mail.from.address' => $emailSetting->from,
            'mail.from.name' => $emailSetting->alias,
        ]);
    }
}

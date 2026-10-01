<?php

namespace App\Providers;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mime\Address;

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
        Paginator::useBootstrapFive();

        $alwaysTo = config('mail.to.address');
        if (!empty($alwaysTo)) {
            Mail::alwaysTo($alwaysTo, config('mail.to.name'));
        }

        $replyTo = config('mail.reply_to.address');
        if (!empty($replyTo)) {
            Mail::alwaysReplyTo($replyTo, config('mail.reply_to.name'));
        }

        Event::listen(MessageSending::class, function (MessageSending $event) {
            $cc = config('mail.cc.address');
            if (!empty($cc)) {
                foreach ($this->parseEmailList((string) $cc) as $email) {
                    $event->message->cc(new Address($email, (string) config('mail.cc.name')));
                }
            }

            $bcc = config('mail.bcc.address');
            if (!empty($bcc)) {
                foreach ($this->parseEmailList((string) $bcc) as $email) {
                    $event->message->bcc(new Address($email, (string) config('mail.bcc.name')));
                }
            }
        });
    }

    /**
     * @return array<int, string>
     */
    private function parseEmailList(string $raw): array
    {
        return collect(explode(',', $raw))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}

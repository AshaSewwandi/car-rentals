<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuestAccountCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public string $temporaryPassword;

    public function __construct(User $user, string $temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function build(): self
    {
        return $this
            ->subject('Your R&A Auto Rentals account is ready')
            ->view('emails.guest-account-created');
    }
}

<?php

namespace App\Mail;

use App\Models\Car;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnerApplicationSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $partner;
    public Car $car;

    public function __construct(User $partner, Car $car)
    {
        $this->partner = $partner;
        $this->car = $car;
    }

    public function build(): self
    {
        return $this
            ->subject('New Partner Application Submitted | R&A Auto Rentals')
            ->view('emails.partner-application-submitted');
    }
}


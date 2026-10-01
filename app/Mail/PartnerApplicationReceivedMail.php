<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnerApplicationReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $partner;
    public Vehicle $vehicle;

    public function __construct(User $partner, Vehicle $vehicle)
    {
        $this->partner = $partner;
        $this->vehicle = $vehicle;
    }

    public function build(): self
    {
        return $this
            ->subject('Thank You for Registering | R&A Auto Rentals')
            ->view('emails.partner-application-received');
    }
}


<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCancelledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $cancelledBy;
    public string $cancelledRole;

    public function __construct(Booking $booking, string $cancelledBy, string $cancelledRole)
    {
        $this->booking = $booking;
        $this->cancelledBy = $cancelledBy;
        $this->cancelledRole = $cancelledRole;
    }

    public function build(): self
    {
        return $this
            ->subject('Rental Trip Cancelled #' . $this->booking->id . ' | R&A Auto Rentals')
            ->view('emails.booking-cancelled');
    }
}

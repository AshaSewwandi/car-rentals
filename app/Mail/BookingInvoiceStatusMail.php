<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingInvoiceStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $stage;

    public function __construct(Booking $booking, string $stage)
    {
        $this->booking = $booking;
        $this->stage = $stage;
    }

    public function build(): self
    {
        $subject = $this->stage === 'completed'
            ? 'Trip Completed Invoice #' . $this->booking->id . ' | R&A Auto Rentals'
            : 'Booking Confirmation Invoice #' . $this->booking->id . ' | R&A Auto Rentals';

        return $this
            ->subject($subject)
            ->view('emails.booking-invoice-status');
    }
}

<?php

namespace App\Mail;

use App\Models\BookingList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingList;

    public function __construct(BookingList $bookingList)
    {
        $this->bookingList = $bookingList;
    }
    public function build()
    {
        $productName = $this->bookingList->product->name ?? 'Product';
        return $this->subject('Product Available - ' . $productName)
            ->markdown('emails.booking-lists.available');
    }
}

<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\OrderComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $comment;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, OrderComment $comment)
    {
        $this->order = $order;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Comment on Order #' . $this->order->id)
            ->markdown('emails.orders.comment');
    }
}

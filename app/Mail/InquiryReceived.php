<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Mail\Mailable;

class InquiryReceived extends Mailable
{
    public function __construct(public Inquiry $inquiry) {}

    public function build(): static
    {
        return $this->subject('Inquiry baru '.$this->inquiry->reference_number)->replyTo($this->inquiry->email, $this->inquiry->name)->view('mail.inquiry');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Recipient;

class SupervisorRegistrationConfirm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The registered recipient
     *
     * @var \App\Models\Recipient;
     */
    public $recipient;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Recipient $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('longserviceawards@gov.bc.ca', 'Long Service Award - Supervisor Confirmation')
                    ->view('emails.supervisorRegistrationConfirm');
    }
}

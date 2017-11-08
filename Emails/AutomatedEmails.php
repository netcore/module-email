<?php

namespace Modules\Email\Emails;

use Modules\Email\Models\AutomatedEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutomatedEmails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var AutomatedEmail
     */
    public $automatedEmail;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param AutomatedEmail $automatedEmail
     * @param User           $user
     */
    public function __construct(AutomatedEmail $automatedEmail, User $user)
    {
        $this->automatedEmail = $automatedEmail;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.automated-email')->subject($this->automatedEmail->title);
    }
}

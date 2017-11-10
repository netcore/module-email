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
     * @var \Illuminate\Config\Repository|mixed
     */
    public $config;

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
        $this->config = config('netcore.module-email');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email::emails.automated-email')->subject($this->automatedEmail->name);
    }
}

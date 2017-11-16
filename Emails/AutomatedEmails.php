<?php

namespace Modules\Email\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Models\AutomatedEmail;

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
     * @var User
     */
    public $secondUser;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    public $config;

    /**
     * Create a new message instance.
     *
     * @param AutomatedEmail $automatedEmail
     * @param                $user
     * @param null           $secondUser
     */
    public function __construct(AutomatedEmail $automatedEmail, $user, $secondUser = null)
    {
        $this->automatedEmail = $automatedEmail;
        $this->user = $user;
        $this->secondUser = $secondUser;
        $this->config = config('netcore.module-email');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = $this->config['automated_emails_template'] ?: 'email::emails.automated-email';

        return $this->view($template)->subject($this->automatedEmail->name);
    }
}

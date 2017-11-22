<?php

namespace Modules\Email\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Entities\AutomatedEmailJob;
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
     * @var
     */
    public $job;

    /**
     * AutomatedEmails constructor.
     *
     * @param AutomatedEmailJob $job
     */
    public function __construct(AutomatedEmailJob $job)
    {
        $this->automatedEmail = $job->automatedEmail;
        $this->user           = $job->user;
        $this->secondUser     = $job->secondUser;
        $this->config         = config('netcore.module-email');
        $this->job            = $job;
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

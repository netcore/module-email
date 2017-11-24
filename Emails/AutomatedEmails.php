<?php

namespace Modules\Email\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Models\AutomatedEmailJob;
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
     * @var array
     */
    public $variables = [];

    /**
     * AutomatedEmails constructor.
     *
     * @param User $user
     * @param $job
     */
    public function __construct(User $user, $job)
    {
        $this->automatedEmail = $job->automatedEmail;
        $this->user           = $user;
        $this->secondUser     = $job->secondUser;
        $this->config         = config('netcore.module-email');
        $this->variables      = $job ? $job->variable_list : [];
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

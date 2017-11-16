<?php

namespace Modules\Email\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Models\Campaign;

class CampaignEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Campaign
     */
    public $campaign;

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
     * @param Campaign $campaign
     * @param null     $user
     */
    public function __construct(Campaign $campaign, $user = null)
    {
        $this->campaign = $campaign;
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
        $template = $this->config['campaign_emails_template'] ?: 'email::emails.campaign-email';

        return $this->view($template)->subject($this->campaign->name);
    }
}

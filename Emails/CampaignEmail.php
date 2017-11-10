<?php

namespace Modules\Email\Emails;

use Modules\Email\Models\Campaign;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CampaignEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var EmailCampaign
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
     * @param EmailCampaign $campaign
     * @param User          $user
     */
    public function __construct(Campaign $campaign, User $user)
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
        return $this->view('email::emails.campaign-email')->subject($this->campaign->name);
    }
}

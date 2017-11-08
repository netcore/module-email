<?php

namespace Modules\Email\Emails;

use Modules\Email\Models\EmailCampaign;
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
     * Create a new message instance.
     *
     * @param EmailCampaign $campaign
     * @param User          $user
     */
    public function __construct(EmailCampaign $campaign, User $user)
    {
        $this->campaign = $campaign;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.campaign-email')->subject($this->campaign->name);
    }
}

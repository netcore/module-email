<?php

namespace Modules\Email\Console;

use Modules\Email\Emails\CampaignEmail;
use Modules\Email\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-campaign:send {campaign}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to users in campaign';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $campaignId = $this->argument('campaign');
        $campaign = Campaign::find($campaignId);

        if (!$campaign) {
            $this->error('[Campaigns] Campaign not found');
            exit;
        }

        $lastUserId = null;
        foreach ($campaign->users()->wherePivot('is_sent', '0')->get() as $user) {

            // Check for lock file
            if (!$campaign->lockFileExists()) {
                $campaign->stop('stopped', $lastUserId);
                exit;
            }

            if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            try {
                Mail::to($user)->send(new CampaignEmail($campaign, $user));

                $campaign->users()->updateExistingPivot($user->id, [
                    'is_sent' => '1',
                    'sent_at' => Carbon::now()
                ]);

            } catch (\Exception $e) {
                $campaign->stop('error', $lastUserId);
                \Log::error($e->getMessage());
                $this->error('[Campaigns] ' . $e->getMessage());
                exit;
            }

            $lastUserId = $user->id;

            $campaign->update([
                'last_user_id' => $lastUserId
            ]);
        }

        $campaign->stop('sent');
    }
}

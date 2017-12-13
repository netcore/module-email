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

        $campaign->update([
            'status' => 'sending'
        ]);

        $lastReceiver = null;
        $campaign->getReceivers()->chunk(100, function ($receivers) use ($campaign, $lastReceiver) {
            foreach ($receivers as $receiver) {

                // Check for lock file
                if (!$campaign->lockFileExists()) {
                    $campaign->stop('stopped', $lastReceiver);
                    exit;
                }

                try {
                    $campaign->sendTo($receiver);
                    $receiver->update([
                        'is_sent' => '1',
                        'sent_at' => Carbon::now()
                    ]);
                } catch (\Exception $e) {
                    $campaign->stop('error', $lastReceiver);
                    $campaign->logs()->create([
                        'email'   => $receiver->email,
                        'type'    => 'error',
                        'message' => $e->getMessage()
                    ]);
                    exit;
                }

                $lastReceiver = $receiver->id;

                $campaign->update([
                    'last_email' => $lastReceiver
                ]);
            }
        });

        $campaign->stop('sent');
    }
}

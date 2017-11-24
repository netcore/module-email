<?php

namespace Modules\Email\Console;

use Modules\Email\Emails\AutomatedEmails;
use Modules\Email\Models\AutomatedEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAutomatedEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automated-emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automated emails';

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
        foreach (AutomatedEmail::period()->active()->get() as $email) {
            if (!$email->checkPeriod()) {
                continue;
            }

            foreach ($email->getUsers() as $user) {
                try {
                    $email->sendTo($user);
                    $email->update([
                        'last_user_id' => $user->id
                    ]);
                } catch (\Exception $e) {
                    $email->logs()->create([
                        'email'   => $user->email,
                        'type'    => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
            }

            $email->update([
                'last_sent_at' => Carbon::now(),
                'last_user_id' => null
            ]);
        }

        foreach (AutomatedEmail::without('translations')->static()->active()->get() as $email) {

            foreach ($email->jobs as $job)
            {

                if ($email->now() || $this->send_at->lte(Carbon::now())) {

                    try {

                        $email->sendTo($job->user, $job);
                        $job->delete();

                    } catch (\Exception $e) {

                        $email->logs()->create([
                            'email'   => $job->user->email,
                            'type'    => 'error',
                            'message' => $e->getMessage()
                        ]);

                    }

                }

            }

        }
    }
}

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
        $emails = AutomatedEmail::active()->get();
        foreach ($emails as $email) {
            if (!$email->checkPeriod()) {
                continue;
            }

            foreach ($email->getUsers() as $user) {
                if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                Mail::to($user)->send(new AutomatedEmails($email, $user));

                $email->update([
                    'last_user_id' => $user->id
                ]);
            }

            $email->update([
                'last_sent_at' => Carbon::now()->startOfDay()->addHour(),
                'last_user_id' => null
            ]);
        }
    }
}

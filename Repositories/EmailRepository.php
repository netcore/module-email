<?php

namespace Modules\Email\Repositories;

use Modules\Email\Models\AutomatedEmail;
use Modules\Email\Models\Subscriber;

class EmailRepository
{
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $config;

    /**
     * EmailRepository constructor.
     */
    public function __construct()
    {
        $this->config = config('netcore.module-email');
    }

    /**
     * @param null $email
     * @return bool
     */
    public function subscribe($email = null): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return Subscriber::updateOrCreate([
            'email' => $email
        ], [
            'user_id' => auth()->check() ? auth()->id() : null
        ]);
    }

    /**
     * @param null $email
     * @return bool
     */
    public function unsubscribe($email = null): bool
    {
        $subscriber = Subscribe::where('email', $email)->first();
        if (!$subscriber) {
            return false;
        }

        return $subscriber->delete();
    }

    /**
     * @param       $key
     * @param       $user
     * @param null  $secondUser
     * @param array $data
     * @return mixed
     */
    public function send($key, $user, $secondUser = null, $data = [])
    {
        $email = AutomatedEmail::where('key', $key)->first();
        if (!$email) {
            return false;
        }

        return $email->createJob($user, $secondUser, $data);
    }

    /**
     * Get filters for searching users
     *
     * @return array
     */
    public function getFilters(): array
    {
        //TODO: Get filters from somewhere in project
        return [];
    }

    /**
     * @return mixed
     */
    public function searchQuery()
    {
        if (request()->get('receivers', 'users') === 'users') {
            $query = User::select(['id', 'email']);

            //TODO: Get filters query from somewhere in project
        } else {
            $query = Subscriber::select(['user_id', 'email']);
        }

        return $query;
    }

    /**
     * @return mixed
     */
    public function searchReceivers()
    {
        $query = $this->searchQuery();

        return datatables()->of($query)->make(true);
    }
}

<?php

namespace Modules\Email\Repositories;

use Illuminate\Support\Collection;
use Modules\Email\Models\AutomatedEmail;
use Modules\Email\Models\Subscriber;

class EmailRepository
{
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $config;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $userModel;

    /**
     * EmailRepository constructor.
     */
    public function __construct()
    {
        $this->config = config('netcore.module-email');
        $this->userModel = config('netcore.module-admin.user.model');
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
     * @param array $data
     * @return mixed
     */
    public function send($key, $user, $data = [])
    {
        $email = AutomatedEmail::where('key', $key)->first();
        if (!$email) {
            return false;
        }

        return $email->createJob($user, $data);
    }

    /**
     * Get filters for searching users
     *
     * @return Collection
     */
    public function getFilters(): Collection
    {
        $filters = method_exists($this->userModel, 'getFilters') ? (new $this->userModel)->getFilters() : [];

        return collect($filters);
    }

    /**
     * @return mixed
     */
    public function searchQuery()
    {
        $receivers = request()->get('receivers', 'users');

        if ($receivers === 'all-users') {
            $query = $this->userModel::select(['id', 'email']);
        } elseif ($receivers === 'users') {
            $query = method_exists($this->userModel, 'getFilterQuery') ? (new $this->userModel)->getFilterQuery() : '';
        } else {
            $query = Subscriber::select(['email']);
        }

        return $query;
    }

    /**
     * @return mixed
     */
    public function searchReceivers()
    {
        $query = $this->searchQuery();

        return datatables()->of($query)->addColumn('checkbox', function ($receiver) {
            return view('email::campaigns.tds.checkbox', compact('receiver'))->render();
        })->rawColumns(['checkbox'])->make(true);
    }
}

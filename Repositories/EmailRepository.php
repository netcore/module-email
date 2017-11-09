<?php

namespace Modules\Email\Repositories;

use Modules\Email\Models\Subscriber;

class EmailRepository
{

    /**
     * EmailRepository constructor.
     */
    public function __construct()
    {
        //
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

        Subscriber::updateOrCreate(['email' => $email], [
            'user_id' => auth()->check() ? auth()->id() : null
        ]);

        return true;
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

        $subscriber->delete();

        return true;
    }

    /**
     * @param $text
     * @param $user
     * @return string
     */
    function replaceUserData($text, $user): string
    {
        $predefined = null;

        if (method_exists($user, 'replaceData')) {
            $predefined = $user->replaceData();
        }

        if (!is_array($predefined)) {
            return $text;
        }

        return str_replace(array_keys($predefined), array_values($predefined), $text);
    }
}

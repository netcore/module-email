<?php
namespace Modules\Email\Traits;

use Illuminate\Foundation\Auth\User;

trait ReplaceVariables {

    /**
     * Replaces variables in email text
     *
     * @param User $user
     * @param array $data
     * @return string
     */
    public function replaceVariables(User $user, $data = []) : string
    {
        $userReplaceable = method_exists($user, 'getReplaceable') ? $user->getReplaceable() : [];
        $replace         = array_merge($data, $userReplaceable);
        $line            = $this->text;

        return preg_replace_callback($this->config['replace_regex'], function ($match) use ($replace) {
            $key = isset($match[1]) ? $match[1] : null;

            return array_get($replace, $key, $match[0]);
        }, $text);
    }

}

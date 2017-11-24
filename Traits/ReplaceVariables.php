<?php
namespace Modules\Email\Traits;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;

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

        foreach ($replace as $key => $value) {
            $line = str_replace(
                ['['.$key.']', '['.Str::upper($key).']', '['.Str::ucfirst($key).']'],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $line
            );
        }

        return $line;
    }

}
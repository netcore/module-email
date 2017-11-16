<?php

if (!function_exists('email')) {

    /**
     * @return \Illuminate\Foundation\Application
     */
    function email()
    {
        return app('email');
    }
}

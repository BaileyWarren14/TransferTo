<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    public static function userNow($format = 'Y-m-d H:i:s')
    {
        return Carbon::now(session('user_timezone', 'UTC'))->format($format);
    }

    public static function userTime($datetime, $format = 'Y-m-d H:i:s')
    {
        return Carbon::parse($datetime)->timezone(session('user_timezone', 'UTC'))->format($format);
    }
}

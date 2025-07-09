<?php

use App\Logging\ActivityLogger;

if (!function_exists('activity')) {
    function activity(): ActivityLogger
    {
        return new ActivityLogger();
    }
}

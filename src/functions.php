<?php

declare(strict_types=1);

use DKulyk\Eloquent\Extensions\Support\PendingDispatch;

if (!function_exists('dispatch_transaction')) {
    function dispatch_transaction($job)
    {
        if ($job instanceof Closure) {
            $job = CallQueuedClosure::create($job);
        }

        return new PendingDispatch($job);
    }
}

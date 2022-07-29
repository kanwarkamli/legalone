<?php

namespace App\Entity;

use Carbon\Carbon;

class LogFactory
{
    public static function create(string $statusCode, string $serviceName, Carbon $loggedAt): Log
    {
        $log = new Log();
        $log->setStatusCode($statusCode);
        $log->setServiceName($serviceName);
        $log->setLoggedAt($loggedAt);

        return $log;
    }
}

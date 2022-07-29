<?php

namespace App\Services;

use App\Entity\LogFactory;
use App\Repository\LogRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class Log
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /*
     *  USER-SERVICE - - [17/Aug/2021:09:21:53 +0000] "POST /users HTTP/1.1" 201
        USER-SERVICE - - [17/Aug/2021:09:21:54 +0000] "POST /users HTTP/1.1" 400
        INVOICE-SERVICE - - [17/Aug/2021:09:21:55 +0000] "POST /invoices HTTP/1.1" 201
     *
     */
    public function parse($file)
    {
        if ($file = fopen($file, "r")) {
            while (($line = fgets($file)) !== false) {
                $line = str_replace(array("\r", "\n"), '', trim($line));

                preg_match_all('/^([\w\-]+)|(\[[^\]]+\])|(\d{3})/is', $line, $matches);

                if (sizeof($matches[0]) == 0) {
                    continue;
                }

                $serviceName = $matches[0][0];
                $loggedAt = $matches[0][1];
                $statusCode = $matches[0][2];

                $entity = LogFactory::create($statusCode, $serviceName, $this->getDate($loggedAt));
                $this->entityManager->persist($entity);
            }

            $this->entityManager->flush();
            $this->entityManager->clear();

            fclose($file);
        }
    }

    private function getDate($date)
    {
        $date = str_replace(['[', ']'], '', $date);
        return Carbon::createFromFormat('d/M/Y:H:i:s O', $date);
    }
}

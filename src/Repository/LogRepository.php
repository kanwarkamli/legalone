<?php

namespace App\Repository;

use App\Entity\Log;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Log>
 *
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    /**
     * @param $filter
     * @return int Returns an array of Log objects
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function queryByParameter($filter): int
    {
        $serviceNames = array_key_exists('serviceNames', $filter)
            ? array_values($filter['serviceNames'])
            : [];

        $statusCode = array_key_exists('statusCode', $filter)
            ? array_values($filter['statusCode'])
            : null;

        $startDate = array_key_exists('startDate', $filter)
            ? array_values($filter['startDate'])
            : null;

        $startDate = !is_null($startDate)
            ? Carbon::parse($startDate[0])->toDateTimeString()
            : null;

        $endDate = array_key_exists('endDate', $filter)
            ? array_values($filter['endDate'])
            : null;

        $endDate = !is_null($endDate)
            ? Carbon::parse($endDate[0])->toDateTimeString()
            : null;

        $qb = $this->createQueryBuilder('l')
            ->select('count(l.id)');

        if (!empty($serviceNames)) {
            $qb
                ->andWhere('l.serviceName IN (:serviceNames)')
                ->setParameter('serviceNames', $serviceNames);
        }

        if (!empty($statusCode)) {
            $qb
                ->andWhere('l.statusCode = :statusCode')
                ->setParameter('statusCode', $statusCode);
        }

        if (!empty($startDate)) {
            $qb
                ->andWhere('l.loggedAt >= :startDate')
                ->setParameter('startDate', $startDate);
        }

        if (!empty($endDate)) {
            $qb
                ->andWhere('l.loggedAt <= :endDate')
                ->setParameter('endDate', $endDate);
        }

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getAllRecordCount(): int
    {
        return $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}

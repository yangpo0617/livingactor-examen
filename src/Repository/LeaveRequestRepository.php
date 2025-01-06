<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\LeaveRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LeaveRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeaveRequest::class);
    }

    /**
     * Recherche par critères
     */
    public function getTotalDaysRequested(
        Employee $employee,
        ?\DateTimeInterface $searchStartDate = null,
        ?\DateTimeInterface $searchEndDate = null,
        array $status = []
    ): int {
        $queryBuilder = $this->createQueryBuilder('lr')
            ->leftJoin('lr.employee', 'e')
            ->andWhere('e.id = :employeeId')
            ->setParameter('employeeId', $employee->getId());

        if ($searchStartDate) {
            $queryBuilder->andWhere('lr.startDate >= :searchStartDate')
                ->setParameter('searchStartDate', $searchStartDate);
        }
        if ($searchEndDate) {
            $queryBuilder->andWhere('lr.endDate <= :searchEndDate')
                ->setParameter('searchEndDate', $searchEndDate);
        }
        if (!empty($status)) {
            $queryBuilder->andWhere('lr.status IN (:status)')
                ->setParameter('status', $status);
        }
        $queryBuilder->select('SUM(lr.endDate - lr.startDate) as totalDays')
            ->groupBy('e.id');
        $result = $queryBuilder->getQuery()->getResult();

        return $result[0]['totalDays'] ?? 0;
    }
}
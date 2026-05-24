<?php

namespace App\Repository;

use App\Entity\ExerciseLog;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExerciseLog>
 */
class ExerciseLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciseLog::class);
    }
    public function totalCaloriesBurned(User $user, \DateTimeInterface $dateToSearch): int
    {
        $begin = (clone $dateToSearch)->setTime(0, 0, 0);
        $end   = (clone $dateToSearch)->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('e')
            // C'est ici que la magie opère 👇
            ->select('SUM(e.caloriesBurned)')
            ->andWhere('e.user = :user')
            ->andWhere('e.date >= :begin')
            ->andWhere('e.date <= :end')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return ExerciseLog[]
     */
    public function findByUserAndDate(User $user, \DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('el')
            ->where('el.user = :user')
            ->andWhere('el.date = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('el.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function sumCaloriesBurnedForDay(User $user, \DateTimeInterface $date): int
    {
        return (int) $this->createQueryBuilder('el')
            ->select('COALESCE(SUM(el.caloriesBurned), 0)')
            ->where('el.user = :user')
            ->andWhere('el.date = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countForDay(User $user, \DateTimeInterface $date): int
    {
        return (int) $this->createQueryBuilder('el')
            ->select('COUNT(el.id)')
            ->where('el.user = :user')
            ->andWhere('el.date = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return ExerciseLog[]
     */
    public function findRecentByUser(User $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('el')
            ->where('el.user = :user')
            ->setParameter('user', $user)
            ->orderBy('el.date', 'DESC')
            ->addOrderBy('el.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
/**
 * @extends ServiceEntityRepository<Exercise>
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    /**
     * @return Exercise[]
     */
    public function findByCategory(string $category = ''): array
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.name', 'ASC');

        if ($category !== '') {
            $qb->where('e.category = :cat')
                ->setParameter('cat', $category);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Exercise[]
     */
    public function search(string $q): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.name LIKE :q')
            ->setParameter('q', "%{$q}%")
            ->orderBy('e.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function totalCaloriesBurned(User $user, \DateTimeInterface $dateToSearch): int
    {
        $begin = (clone $dateToSearch)->setTime(0, 0, 0);
        $end   = (clone $dateToSearch)->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('e')
            ->select('SUM(e.calories)') // Vérifie que ta propriété s'appelle bien "calories"
            ->andWhere('e.user = :user')
            ->andWhere('e.date >= :begin') // Vérifie que ta propriété s'appelle bien "date"
            ->andWhere('e.date <= :end')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

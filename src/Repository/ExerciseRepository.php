<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
}

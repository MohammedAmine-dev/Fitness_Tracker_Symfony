<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Meal>
 */
class MealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meal::class);
    }
    public function totalCaloriesConsumed(User $user, \DateTimeInterface $dateToSearch): int
    {
        $begin = (clone $dateToSearch)->setTime(0, 0, 0);
        $end   = (clone $dateToSearch)->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('m')
            ->select('SUM(m.calories)')
            ->andWhere('m.user = :user')
            // On utilise "m.date" ici 👇
            ->andWhere('m.date >= :begin')
            ->andWhere('m.date <= :end')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function totalProtein(User $user, \DateTimeInterface $dateToSearch): int
    {
        $begin = (clone $dateToSearch)->setTime(0, 0, 0);
        $end   = (clone $dateToSearch)->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('m')
            ->select('SUM(m.protein)') // On additionne les protéines
            ->andWhere('m.user = :user')
            ->andWhere('m.date >= :begin')
            ->andWhere('m.date <= :end')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalCarbs(User $user, \DateTimeInterface $dateToSearch): int
    {
        $begin = (clone $dateToSearch)->setTime(0, 0, 0);
        $end   = (clone $dateToSearch)->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('m')
            ->select('SUM(m.carbs)') // On additionne les glucides
            ->andWhere('m.user = :user')
            ->andWhere('m.date >= :begin')
            ->andWhere('m.date <= :end')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function totalFat(User $user, \DateTimeInterface $dateToSearch): int
    {
        $begin = (clone $dateToSearch)->setTime(0, 0, 0);
        $end   = (clone $dateToSearch)->setTime(23, 59, 59);

        return (int) $this->createQueryBuilder('m')
            ->select('SUM(m.fat)') // On additionne les lipides
            ->andWhere('m.user = :user')
            ->andWhere('m.date >= :begin')
            ->andWhere('m.date <= :end')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }
    /**
     * Retourne la liste des repas d'un utilisateur pour une date et un type donné.
     * @return Meal[]
     */
    public function findByType(User $user, \DateTimeInterface $dateToSearch, string $type): array
    {
        $begin = (clone $dateToSearch)->setTime(0, 0, 0);
        $end   = (clone $dateToSearch)->setTime(23, 59, 59);

        return $this->createQueryBuilder('m')
            ->andWhere('m.user = :user')
            ->andWhere('m.date >= :begin')
            ->andWhere('m.date <= :end')
            // C'est ici qu'on cible la vraie propriété "mealType" de ton entité
            ->andWhere('m.mealType = :type')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult(); // getResult() renvoie un tableau d'objets Meal, pas juste un chiffre
    }
}

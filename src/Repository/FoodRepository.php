<?php

namespace App\Repository;

use App\Entity\Food;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    /**
     * @return array{foods: Food[], meta: array}
     */
    public function search(array $filters): array
    {
        $search = trim((string)($filters['search'] ?? ''));
        $category = (string)($filters['category'] ?? '');
        $page = max(1, (int)($filters['page'] ?? 1));
        $limit = (int)($filters['limit'] ?? 24);
        $limit = max(6, min(48, $limit));

        $qb = $this->createQueryBuilder('f');

        if ($search !== '') {
            $qb->andWhere('LOWER(f.name) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        if ($category !== '' && $category !== 'All' && in_array($category, Food::CATEGORIES, true)) {
            $qb->andWhere('f.category = :category')
                ->setParameter('category', $category);
        }

        $countQb = clone $qb;
        $total = (int) $countQb->select('COUNT(f.id)')->getQuery()->getSingleScalarResult();
        $totalPages = max(1, (int) ceil($total / $limit));
        $page = min($page, $totalPages);

        $foods = $qb
            ->orderBy('f.name', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return [
            'foods' => $foods,
            'meta' => [
                'total' => $total,
                'total_pages' => $totalPages,
                'page' => $page,
                'limit' => $limit,
                'search' => $search,
                'category' => $category,
            ],
        ];
    }
}

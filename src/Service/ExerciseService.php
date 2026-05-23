<?php

namespace App\Service;

use App\Entity\Exercise;
use App\Entity\ExerciseLog;
use App\Entity\User;
use App\Repository\ExerciseLogRepository;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExerciseService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExerciseRepository $exerciseRepo,
        private ExerciseLogRepository $logRepo,
    ) {
    }

    public function getCategories(): array
    {
        return [
            Exercise::CATEGORY_CARDIO,
            Exercise::CATEGORY_STRENGTH,
            Exercise::CATEGORY_CALISTHENICS,
            Exercise::CATEGORY_SPORTS,
        ];
    }

    /**
     * @return Exercise[]
     */
    public function getCatalog(string $category = ''): array
    {
        return $this->exerciseRepo->findByCategory($category);
    }

    /**
     * @return ExerciseLog[]
     */
    public function getRecentLogs(?User $user, int $limit = 5): array
    {
        if (!$user) {
            return [];
        }

        return $this->logRepo->findRecentByUser($user, $limit);
    }

    public function addLog(User $user, ExerciseLog $log): void
    {
        $log->setUser($user)
            ->setDate(new \DateTimeImmutable());

        if ($log->getCaloriesBurned() === null) {
            $log->setCaloriesBurned(0);
        }

        $this->em->persist($log);
        $this->em->flush();
    }

    public function deleteLog(ExerciseLog $log, User $user): void
    {
        if ($log->getUser() !== $user) {
            return;
        }

        $this->em->remove($log);
        $this->em->flush();
    }

    public function saveCatalogEntry(Exercise $exercise): void
    {
        $this->em->persist($exercise);
        $this->em->flush();
    }

    public function deleteCatalogEntry(Exercise $exercise): void
    {
        $this->em->remove($exercise);
        $this->em->flush();
    }
}

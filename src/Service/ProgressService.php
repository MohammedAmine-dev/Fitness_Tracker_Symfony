<?php
namespace App\Service;

use App\Entity\WeightLog;
use App\Entity\User;
use App\Repository\WeightLogRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProgressService
{
    public function __construct(
        private EntityManagerInterface $em,
        private WeightLogRepository $weightLogRepository
    ) {}

    public function getWeightHistory(?User $user): array
    {
        if (!$user) return [];
        return $this->weightLogRepository->findRecentByUser($user);
    }

    public function logWeight(?User $user, WeightLog $log): void
    {
        if ($user) $log->setUser($user);
        $this->em->persist($log);
        $this->em->flush();
    }
}
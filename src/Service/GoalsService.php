<?php
namespace App\Service;

use App\Entity\Goal;
use App\Entity\User;
use App\Repository\GoalRepository;
use Doctrine\ORM\EntityManagerInterface;

class GoalsService
{
    public function __construct(
        private EntityManagerInterface $em,
        private GoalRepository $goalRepository
    ) {}

    public function getOrCreateGoal(?User $user): Goal
    {
        if (!$user) {
            return new Goal(); // Fallback for testing without login
        }
        $goal = $this->goalRepository->findOneBy(['user' => $user]);
        if (!$goal) {
            $goal = new Goal();
            $goal->setUser($user);
        }
        return $goal;
    }

    public function save(Goal $goal): void
    {
        $this->em->persist($goal);
        $this->em->flush();
    }
}
<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\GoalRepository;

#[ORM\Entity(repositoryClass: GoalRepository::class)]
class Goal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\Positive(message: "Target weight must be positive")]
    private ?float $targetWeight = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "Daily calories must be positive")]
    private ?int $dailyCalories = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero(message: "Weekly workouts cannot be negative")]
    private ?int $weeklyWorkouts = null;

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(User $user): self { $this->user = $user; return $this; }
    public function getTargetWeight(): ?float { return $this->targetWeight; }
    public function setTargetWeight(?float $targetWeight): self { $this->targetWeight = $targetWeight; return $this; }
    public function getDailyCalories(): ?int { return $this->dailyCalories; }
    public function setDailyCalories(?int $dailyCalories): self { $this->dailyCalories = $dailyCalories; return $this; }
    public function getWeeklyWorkouts(): ?int { return $this->weeklyWorkouts; }
    public function setWeeklyWorkouts(?int $weeklyWorkouts): self { $this->weeklyWorkouts = $weeklyWorkouts; return $this; }
}
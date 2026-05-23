<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
#[ORM\Table(name: 'exercises')]
class Exercise
{
    public const CATEGORY_CARDIO = 'cardio';
    public const CATEGORY_STRENGTH = 'strength';
    public const CATEGORY_CALISTHENICS = 'calisthenics';
    public const CATEGORY_SPORTS = 'sports';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [
        self::CATEGORY_CARDIO,
        self::CATEGORY_STRENGTH,
        self::CATEGORY_CALISTHENICS,
        self::CATEGORY_SPORTS,
    ])]
    private ?string $category = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public static function getCategoryChoices(): array
    {
        return [
            'Cardio' => self::CATEGORY_CARDIO,
            'Strength' => self::CATEGORY_STRENGTH,
            'Calisthenics' => self::CATEGORY_CALISTHENICS,
            'Sports' => self::CATEGORY_SPORTS,
        ];
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ApiResource]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The name of the skill.", example: "Next.JS")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The name of the icon for the skill.", example: "SiNextdotjs")]
    private ?string $icon = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SkillCategory $category = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getIcon(): ?string { return $this->icon; }
    public function setIcon(string $icon): static { $this->icon = $icon; return $this; }
    public function getCategory(): ?SkillCategory { return $this->category; }
    public function setCategory(?SkillCategory $category): static { $this->category = $category; return $this; }
}

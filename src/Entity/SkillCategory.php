<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\SkillCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillCategoryRepository::class)]
#[ApiResource]
class SkillCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The title of the skill category.", example: "Web & Frontend")]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The name of the icon for the category.", example: "HiCode")]
    private ?string $icon = null;

    #[ORM\OneToMany(targetEntity: Skill::class, mappedBy: 'category')]
    private Collection $skills;

    public function __construct() { $this->skills = new ArrayCollection(); }
    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getIcon(): ?string { return $this->icon; }
    public function setIcon(string $icon): static { $this->icon = $icon; return $this; }
    /** @return Collection<int, Skill> */
    public function getSkills(): Collection { return $this->skills; }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
#[ApiResource]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The job title or role.", example: "Technical Specialist - Google Technical Solutions")]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The name of the company.", example: "TDCX")]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The location of the job.", example: "Kuala Lumpur")]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The time period of the employment.", example: "Sep 2022 - Nov 2023")]
    private ?string $date = null;

    #[ORM\Column(type: Types::TEXT)]
    #[ApiProperty(description: "A brief summary of the role.", example: "Dedicated myself to specializing in Google Tracking products.")]
    private ?string $summary = null;

    #[ORM\Column]
    #[ApiProperty(description: "A list of key responsibilities and achievements.", example: "[{\"point\": \"Proficiently implemented Google Tracking Tools...\"}]")]
    private array $responsibilities = [];

    public function getId(): ?int { return $this->id; }
    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }
    public function getCompany(): ?string { return $this->company; }
    public function setCompany(string $company): static { $this->company = $company; return $this; }
    public function getLocation(): ?string { return $this->location; }
    public function setLocation(string $location): static { $this->location = $location; return $this; }
    public function getDate(): ?string { return $this->date; }
    public function setDate(string $date): static { $this->date = $date; return $this; }
    public function getSummary(): ?string { return $this->summary; }
    public function setSummary(string $summary): static { $this->summary = $summary; return $this; }
    public function getResponsibilities(): array { return $this->responsibilities; }
    public function setResponsibilities(array $responsibilities): static { $this->responsibilities = $responsibilities; return $this; }
}

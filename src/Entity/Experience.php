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

    #[ORM\Column(type: Types::TEXT)]
    #[ApiProperty(description: "A brief summary of the role.", example: "Dedicated myself to specializing in Google Tracking products.")]
    private ?string $summary = null;

    #[ORM\Column]
    #[ApiProperty(description: "A list of key responsibilities and achievements.", example: "[{\"point\": \"Proficiently implemented Google Tracking Tools...\"}]")]
    private array $responsibilities = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[ApiProperty(description: "The start date of the employment.", example: "2024-09-01")]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[ApiProperty(description: "The end date of the employment. Null if current.", example: "2024-08-31")]
    private ?\DateTimeInterface $endDate = null;

    public function getId(): ?int { return $this->id; }
    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }
    public function getCompany(): ?string { return $this->company; }
    public function setCompany(string $company): static { $this->company = $company; return $this; }
    public function getLocation(): ?string { return $this->location; }
    public function setLocation(string $location): static { $this->location = $location; return $this; }
    public function getSummary(): ?string { return $this->summary; }
    public function setSummary(string $summary): static { $this->summary = $summary; return $this; }
    public function getResponsibilities(): array { return $this->responsibilities; }
    public function setResponsibilities(array $responsibilities): static { $this->responsibilities = $responsibilities; return $this; }
    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $startDate): static { $this->startDate = $startDate; return $this; }
    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; }
    public function setEndDate(?\DateTimeInterface $endDate): static { $this->endDate = $endDate; return $this; }
}

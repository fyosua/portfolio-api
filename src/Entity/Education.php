<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\EducationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
#[ApiResource]
class Education
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The name of the degree obtained.", example: "Bachelor of Computer Science")]
    private ?string $degree = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The name of the university.", example: "Universitas Bina Nusantara")]
    private ?string $university = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The time period of the education.", example: "Aug 2015 - Jun 2020")]
    private ?string $period = null;

    #[ORM\Column(type: Types::JSON)]
    #[ApiProperty(description: "A list of key details or achievements during the education.", example: "[\"Built a solid foundation in computer science...\", \"Gained expertise in programming languages...\"]")]
    private array $details = [];

    public function getId(): ?int { return $this->id; }
    public function getDegree(): ?string { return $this->degree; }
    public function setDegree(string $degree): static { $this->degree = $degree; return $this; }
    public function getUniversity(): ?string { return $this->university; }
    public function setUniversity(string $university): static { $this->university = $university; return $this; }
    public function getPeriod(): ?string { return $this->period; }
    public function setPeriod(string $period): static { $this->period = $period; return $this; }
    public function getDetails(): array { return $this->details; }
    public function setDetails(array $details): static { $this->details = $details; return $this; }
}

<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\EducationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(uriTemplate: '/educations') // This forces the plural URL
    ]
)]
class Education
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = 1;

    #[ORM\Column(length: 255)]
    private ?string $degree = null;

    #[ORM\Column(length: 255)]
    private ?string $university = null;

    #[ORM\Column(length: 255)]
    private ?string $period = null;

    #[ORM\Column(type: Types::JSON)]
    private array $details = [];

    public function __construct()
    {
        $this->id = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(string $degree): static
    {
        $this->degree = $degree;

        return $this;
    }

    public function getUniversity(): ?string
    {
        return $this->university;
    }

    public function setUniversity(string $university): static
    {
        $this->university = $university;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): static
    {
        $this->details = $details;

        return $this;
    }
}

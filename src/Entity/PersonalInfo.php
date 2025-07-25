<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PersonalInfoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonalInfoRepository::class)]
#[ApiResource]
class PersonalInfo
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = 1;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "Date of birth.", example: "07-05-1997")]
    private ?string $dob = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "Nationality.", example: "Indonesian")]
    private ?string $nationality = null;

    public function __construct() { $this->id = 1; }
    public function getId(): ?int { return $this->id; }
    public function getDob(): ?string { return $this->dob; }
    public function setDob(string $dob): static { $this->dob = $dob; return $this; }
    public function getNationality(): ?string { return $this->nationality; }
    public function setNationality(string $nationality): static { $this->nationality = $nationality; return $this; }
}

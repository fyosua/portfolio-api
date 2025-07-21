<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ApiResource]
class Profile
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = 1;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The full name of the user.", example: "Yosua Ferdian")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The professional title or headline.", example: "Technical Specialist & Web FullStack Developer")]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The contact email address.", example: "ferdianyosua@gmail.com")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The contact phone number.", example: "+601127817121")]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "URL to the LinkedIn profile.", example: "https://www.linkedin.com/in/yosua-ferdian-a1a929116/")]
    private ?string $linkedin = null;

    public function __construct()
    {
        $this->id = 1;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(string $phone): static { $this->phone = $phone; return $this; }
    public function getLinkedin(): ?string { return $this->linkedin; }
    public function setLinkedin(string $linkedin): static { $this->linkedin = $linkedin; return $this; }
}

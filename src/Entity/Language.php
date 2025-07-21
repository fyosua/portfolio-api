<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
#[ApiResource]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The name of the language.", example: "Indonesian")]
    private ?string $lang = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(description: "The proficiency level.", example: "Native Speaker")]
    private ?string $level = null;

    public function getId(): ?int { return $this->id; }
    public function getLang(): ?string { return $this->lang; }
    public function setLang(string $lang): static { $this->lang = $lang; return $this; }
    public function getLevel(): ?string { return $this->level; }
    public function setLevel(string $level): static { $this->level = $level; return $this; }
}

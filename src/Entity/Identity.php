<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping as ORM;

#[MappedSuperclass]
class Identity
{
    #[ORM\Column(length: 50)]
    protected ?string $firstname = null;

    #[ORM\Column(length: 50)]
    protected ?string $lastname = null;

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }
}
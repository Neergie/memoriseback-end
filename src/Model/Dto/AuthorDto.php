<?php

namespace App\Model\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class AuthorDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
        max: 50,
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]
        public string $firstname,

        #[Assert\NotBlank]
        #[Assert\Length(
        max: 50,
        maxMessage: 'Your last name cannot be longer than {{ limit }} characters',
    )]
        public string $lastname,
        public ?\DateTimeInterface $birthday,

    ) {
    }
}
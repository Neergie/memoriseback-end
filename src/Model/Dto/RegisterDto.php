<?php

namespace App\Model\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterDto
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

        #[Assert\NotBlank]
        #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(
        min: 5,
        minMessage: 'Your password must be at least {{ limit }} characters long',
    )]
        public string $password,
    ) {
    }
}
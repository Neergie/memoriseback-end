<?php

namespace App\Model\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class EditorDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
        max: 255,
        maxMessage: 'Your name cannot be longer than {{ limit }} characters',
    )]
        public string $name,

    ) {
    }
}
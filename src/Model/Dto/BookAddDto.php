<?php

namespace App\Model\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class BookAddDto
{
    public function __construct(
        #[Assert\Length(
        max: 255,
        maxMessage: 'Your title cannot be longer than {{ limit }} characters',
    )]
        public string $title,
        public string $description,
        public array $authors,
        public array $genres,
        public array $editors,
        public string $isbn,
        public \DateTimeInterface $publicationDate,
        #[Assert\PositiveOrZero]
        public int $stock,
        #[Assert\PositiveOrZero]
        public int $price,
        public bool $ebook,
        public ?string $coverImage
    ) {
    }
}
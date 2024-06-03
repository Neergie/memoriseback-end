<?php

namespace App\Model\Dto;

class BasketDto
{
    public function __construct(
        public array $basket,
    ) {
    }
}
<?php

namespace App\Entity;

use App\Repository\BookOrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookOrderRepository::class)]
class BookOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'book_orders')]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'book_orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order_entity = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    #[Assert\Positive]
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getOrderEntity(): ?Order
    {
        return $this->order_entity;
    }

    public function setOrderEntity(?Order $order_entity): static
    {
        $this->order_entity = $order_entity;

        return $this;
    }
}

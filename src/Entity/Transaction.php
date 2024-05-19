<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $payment_status = null;

    #[ORM\Column(length: 255)]
    private ?string $payment_method = null;

    #[ORM\OneToOne(mappedBy: 'transaction', cascade: ['persist', 'remove'])]
    private ?Order $order_entity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(string $payment_status): static
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): static
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getOrderEntity(): ?Order
    {
        return $this->order_entity;
    }

    public function setOrderEntity(Order $order_entity): static
    {
        // set the owning side of the relation if necessary
        if ($order_entity->getTransaction() !== $this) {
            $order_entity->setTransaction($this);
        }

        $this->order_entity = $order_entity;

        return $this;
    }
}

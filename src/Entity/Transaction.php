<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

enum PaymentStatus: string
{
    case PAID = 'PAID';
    case IN_PROGRESS = 'IN_PROGRESS';
}

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', enumType: PaymentStatus::class)]
    private ?PaymentStatus $paymentStatus = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentMethod = null;

    #[ORM\OneToOne(mappedBy: 'transaction', cascade: ['persist', 'remove'])]
    #[Ignore]
    private ?Order $orderEntity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentStatus(): ?PaymentStatus
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(PaymentStatus $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    #[Ignore]
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    #[Ignore]
    public function getOrderEntity(): ?Order
    {
        return $this->orderEntity;
    }

    public function setOrderEntity(Order $orderEntity): static
    {
        // set the owning side of the relation if necessary
        if ($orderEntity->getTransaction() !== $this) {
            $orderEntity->setTransaction($this);
        }

        $this->orderEntity = $orderEntity;

        return $this;
    }
}
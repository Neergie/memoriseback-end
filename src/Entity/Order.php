<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Ignore;

enum DeliveryState: string
{
    case DONE = 'DONE';
    case SHIPPED = 'SHIPPED';
    case IN_TREATMENT = 'IN_TREATMENT';
}

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(type: 'string', enumType: DeliveryState::class)]
    private ?DeliveryState $deliveryState = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private ?float $totalPrice = null;

    #[ORM\OneToOne(inversedBy: 'orderEntity', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Transaction $transaction = null;

    /**
     * @var Collection<int, BookOrder>
     */
    #[ORM\OneToMany(targetEntity: BookOrder::class, mappedBy: 'orderEntity')]
    private Collection $bookOrders;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[Ignore]
    private ?User $userOrder = null;

    public function __construct()
    {
        $this->bookOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getDeliveryState(): ?DeliveryState
    {
        return $this->deliveryState;
    }

    public function setDeliveryState(DeliveryState $deliveryState): static
    {
        $this->deliveryState = $deliveryState;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(Transaction $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * @return Collection<int, BookOrder>
     */
    public function getBookOrders(): Collection
    {
        return $this->bookOrders;
    }

    public function addBookOrder(BookOrder $bookOrder): static
    {
        if (!$this->bookOrders->contains($bookOrder)) {
            $this->bookOrders->add($bookOrder);
            $bookOrder->setOrderEntity($this);
        }

        return $this;
    }

    public function removeBookOrder(BookOrder $bookOrder): static
    {
        if ($this->bookOrders->removeElement($bookOrder)) {
            // set the owning side to null (unless already changed)
            if ($bookOrder->getOrderEntity() === $this) {
                $bookOrder->setOrderEntity(null);
            }
        }

        return $this;
    }

    #[Ignore]
    public function getUserOrder(): ?User
    {
        return $this->userOrder;
    }

    public function setUserOrder(?User $userOrder): static
    {
        $this->userOrder = $userOrder;

        return $this;
    }
}
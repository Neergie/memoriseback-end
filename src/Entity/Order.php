<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(length: 255)]
    private ?string $delivery_stats = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private ?float $total_price = null;

    #[ORM\OneToOne(inversedBy: 'order_entity', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Transaction $transaction = null;

    /**
     * @var Collection<int, BookOrder>
     */
    #[ORM\OneToMany(targetEntity: BookOrder::class, mappedBy: 'order_entity')]
    private Collection $book_orders;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user_order = null;

    public function __construct()
    {
        $this->book_orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getDeliveryStats(): ?string
    {
        return $this->delivery_stats;
    }

    public function setDeliveryStats(string $delivery_stats): static
    {
        $this->delivery_stats = $delivery_stats;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): static
    {
        $this->total_price = $total_price;

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
        return $this->book_orders;
    }

    public function addBookOrder(BookOrder $bookOrder): static
    {
        if (!$this->book_orders->contains($bookOrder)) {
            $this->book_orders->add($bookOrder);
            $bookOrder->setOrderEntity($this);
        }

        return $this;
    }

    public function removeBookOrder(BookOrder $bookOrder): static
    {
        if ($this->book_orders->removeElement($bookOrder)) {
            // set the owning side to null (unless already changed)
            if ($bookOrder->getOrderEntity() === $this) {
                $bookOrder->setOrderEntity(null);
            }
        }

        return $this;
    }

    public function getUserOrder(): ?User
    {
        return $this->user_order;
    }

    public function setUserOrder(?User $user_order): static
    {
        $this->user_order = $user_order;

        return $this;
    }
}

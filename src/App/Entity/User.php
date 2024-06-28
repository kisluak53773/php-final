<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Common\Collections\Collection;

#[Entity]
#[Table('users')]
class User
{
    #[Id]
    #[Column('user_id'), GeneratedValue]
    private int $id;

    #[Column]
    private string $email;

    #[Column]
    private string $password;

    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    #[Column(name: 'updated_at')]
    private DateTime $updatedAt;

    #[OneToMany(targetEntity: Order::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): User
    {
        $order->setUser($this);

        $this->orders->add($order);

        return $this;
    }
}
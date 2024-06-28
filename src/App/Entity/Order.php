<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use DateTime;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('orders')]
class Order
{
    #[Id]
    #[Column('order_id'), GeneratedValue]
    private int $id;

    #[Column]
    private string $product;

    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    #[Column(name: 'updated_at')]
    private DateTime $updatedAt;

    #[ManyToOne(inversedBy: 'orders')]
    #[JoinColumn(name: "user_id", referencedColumnName: "user_id")]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function setProduct(string $product): void
    {
        $this->product = $product;
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}
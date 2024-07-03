<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;

class EntityService
{
    public static function convertOrderIntoArray(Order $order): array
    {
        return [
            'id' => $order->getId(),
            'product' => $order->getStatus(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $order->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    public static function convertProductIntoArray(Product $product) {
        return [
            'id'=> $product->getId(),
            'name'=> $product->getName(),
            'price' => $product->getPrice(),
            'createdAt' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $product->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
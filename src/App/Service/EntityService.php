<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;

class EntityService
{
    public static function convertOrderIntoArray(Order $order): array
    {
        return [
            'id' => $order->getId(),
            'product' => $order->getProduct(),
            'createdAt' => $order->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $order->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
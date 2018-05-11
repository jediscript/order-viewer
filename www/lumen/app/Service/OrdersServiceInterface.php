<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Service;

use App\Domain\ValueObject\OrderId;

interface OrdersServiceInterface
{
    public function getOne(OrderId $orderId): string;

    public function getMany(array $orderIds);
}

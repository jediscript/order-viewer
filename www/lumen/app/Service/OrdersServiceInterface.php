<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Service;

use App\Domain\ValueObject\TrackingNumber;

interface OrdersServiceInterface
{
    public function displayOrder(TrackingNumber $trackingNumber);

    public function displayOrders(array $trackingNumbers);
}

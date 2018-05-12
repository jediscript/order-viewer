<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Service;

use App\Domain\ValueObject\TrackingNumber;

interface OrdersServiceInterface
{
    public function getOne(TrackingNumber $trackingNumber): string;

    public function getMany(array $trackingNumbers);
}

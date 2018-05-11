<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Http\Controllers;

use App\Domain\ValueObject\OrderId;
use App\Service\CustomerOrdersService;

class GetOrdersDetailController extends Controller
{
    private $customerOrdersService;

    public function __construct(CustomerOrdersService $customerOrdersService)
    {
        $this->customerOrdersService = $customerOrdersService;
    }

    public function getOne(string $orderId)
    {
        return $this->customerOrdersService->getOne(OrderId::fromString($orderId));
    }

    public function getMany(string $orderIds)
    {
        // strips all whitespaces and explode to an array
        $orderIds = preg_replace('/\s+/', '', $orderIds);
        $orderIds = explode(',', $orderIds);

        return $this->customerOrdersService->getMany($orderIds);
    }
}

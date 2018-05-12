<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Http\Controllers;

use App\Domain\ValueObject\TrackingNumber;
use App\Service\CustomerOrdersService;

class GetOrdersDetailController extends Controller
{
    private $customerOrdersService;

    public function __construct(CustomerOrdersService $customerOrdersService)
    {
        $this->customerOrdersService = $customerOrdersService;
    }

    /**
     * @param string $trackingNumber
     * @return string
     */
    public function getOne(string $trackingNumber)
    {
        return $this->customerOrdersService->getOne(TrackingNumber::fromString($trackingNumber));
    }

    /**
     * @param string $trackingNumbers
     */
    public function getMany(string $trackingNumbers)
    {
        // strips all whitespaces and explode to an array
        $trackingNumbers = preg_replace('/\s+/', '', $trackingNumbers);
        $trackingNumbers = explode(',', $trackingNumbers);

        $this->customerOrdersService->getMany($trackingNumbers);
    }
}

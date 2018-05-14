<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Http\Controllers;

use App\Domain\ValueObject\TrackingNumber;
use App\Service\CustomerOrdersService;
use Illuminate\Http\Request;

class GetOrdersDetailController extends Controller
{
    private $customerOrdersService;

    public function __construct(CustomerOrdersService $customerOrdersService)
    {
        $this->customerOrdersService = $customerOrdersService;
    }

    /**
     * @param string $trackingNumber
     */
    public function displayOrder(string $trackingNumber)
    {
        $this->customerOrdersService->displayOrder(TrackingNumber::fromString($trackingNumber));
    }

    /**
     * @param Request $request
     */
    public function displayOrders(Request $request)
    {
        $trackingNumbers = $request->input('trackno');
        $this->customerOrdersService->displayOrders($trackingNumbers);
    }
}

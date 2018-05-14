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
     * @return string
     */
    public function getOne(string $trackingNumber)
    {
        return $this->customerOrdersService->getOne(TrackingNumber::fromString($trackingNumber));
    }

    /**
     * @param Request $request
     */
    public function getMany(Request $request)
    {
        $trackingNumbers = $request->input('trackno');
        $this->customerOrdersService->getMany($trackingNumbers);
    }
}

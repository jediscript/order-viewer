<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Domain\Entity;

use App\Domain\ValueObject\DeliveryStatus;
use App\Domain\ValueObject\OrderId;
use App\Domain\ValueObject\TrackingNumber;
use Illuminate\Support\Collection;
use Money\Money;

class Order
{
    /**
     * @var OrderId
     */
    private $orderId;
    /**
     * @var TrackingNumber
     */
    private $trackingNumber;
    /**
     * @var Collection
     */
    private $turnAroundTime;
    /**
     * @var DeliveryStatus
     */
    private $status;
    /**
     * @var Money
     */
    private $subTotal;
    /**
     * @var Money
     */
    private $shipping;
    /**
     * @var Money
     */
    private $tax;
    /**
     * @var Money
     */
    private $fee;
    /**
     * @var Money
     */
    private $insurance;
    /**
     * @var Money
     */
    private $discount;
    /**
     * @var Money
     */
    private $total;
    /**
     * @var Money
     */
    private $shippingFee;
    /**
     * @var Money
     */
    private $insuranceFee;
    /**
     * @var Money
     */
    private $transactionFee;

    /**
     * Order constructor.
     * @param OrderId $orderId
     * @param TrackingNumber $trackingNumber
     * @param Collection $turnAroundTime
     * @param DeliveryStatus $status
     * @param Money $subTotal
     * @param Money $shipping
     * @param Money $tax
     * @param Money $fee
     * @param Money $insurance
     * @param Money $discount
     * @param Money $total
     * @param Money $shippingFee
     * @param Money $insuranceFee
     * @param Money $transactionFee
     */
    public function __construct(
        OrderId $orderId,
        TrackingNumber $trackingNumber,
        Collection $turnAroundTime,
        DeliveryStatus $status,
        Money $subTotal,
        Money $shipping,
        Money $tax,
        Money $fee,
        Money $insurance,
        Money $discount,
        Money $total,
        Money $shippingFee,
        Money $insuranceFee,
        Money $transactionFee
    ) {
        $this->orderId = $orderId;
        $this->trackingNumber = $trackingNumber;
        $this->turnAroundTime = $turnAroundTime;
        $this->status = $status;
        $this->subTotal = $subTotal;
        $this->shipping = $shipping;
        $this->tax = $tax;
        $this->fee = $fee;
        $this->insurance = $insurance;
        $this->discount = $discount;
        $this->total = $total;
        $this->shippingFee = $shippingFee;
        $this->insuranceFee = $insuranceFee;
        $this->transactionFee = $transactionFee;
    }

    /**
     * @return OrderId
     */
    public function getOrderId(): OrderId
    {
        return $this->orderId;
    }

    /**
     * @return TrackingNumber
     */
    public function getTrackingNumber(): TrackingNumber
    {
        return $this->trackingNumber;
    }

    /**
     * @return Collection
     */
    public function getTurnAroundTime(): Collection
    {
        return $this->turnAroundTime;
    }

    /**
     * @return DeliveryStatus
     */
    public function getStatus(): DeliveryStatus
    {
        return $this->status;
    }

    /**
     * @return Money
     */
    public function getSubTotal(): Money
    {
        return $this->subTotal;
    }

    /**
     * @return Money
     */
    public function getShipping(): Money
    {
        return $this->shipping;
    }

    /**
     * @return Money
     */
    public function getTax(): Money
    {
        return $this->tax;
    }

    /**
     * @return Money
     */
    public function getFee(): Money
    {
        return $this->fee;
    }

    /**
     * @return Money
     */
    public function getInsurance(): Money
    {
        return $this->insurance;
    }

    /**
     * @return Money
     */
    public function getDiscount(): Money
    {
        return $this->discount;
    }

    /**
     * @return Money
     */
    public function getTotal(): Money
    {
        return $this->total;
    }

    /**
     * @return Money
     */
    public function getShippingFee(): Money
    {
        return $this->shippingFee;
    }

    /**
     * @return Money
     */
    public function getInsuranceFee(): Money
    {
        return $this->insuranceFee;
    }

    /**
     * @return Money
     */
    public function getTransactionFee(): Money
    {
        return $this->transactionFee;
    }
}

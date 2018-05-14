<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Service;

use App\Domain\Entity\Order;
use App\Domain\ValueObject\DeliveryStatus;
use App\Domain\ValueObject\OrderId;
use App\Domain\ValueObject\TrackingNumber;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Money\Currency;
use Money\Money;

class CustomerOrdersService implements OrdersServiceInterface
{
    /**
     * @var Client
     */
    private $ordersClient;

    /**
     * @var float
     */
    private $totalCollection;

    /**
     * @var float
     */
    private $totalSales;

    public function __construct(Client $ordersClient)
    {
        $this->ordersClient = $ordersClient;
    }

    /**
     * @param TrackingNumber $trackingNumber
     */
    public function displayOrder(TrackingNumber $trackingNumber)
    {
        $response = $this->ordersClient->request('GET', (string)$trackingNumber, [
            'headers' => [
                'X-Time-Zone' => 'Asia/Manila',
            ]
        ]);

        $order = $this->createOrder(json_decode($response->getBody(), true));

        $this->printOrder($order);
    }

    /**
     * @param array $trackingNumbers
     */
    public function displayOrders(array $trackingNumbers)
    {
        $totalOrders = count($trackingNumbers);

        $requests = function ($totalOrders, $trackingNumbers) {
            for ($i = 0; $i < $totalOrders; $i++) {
                yield new Request('GET', env('ORDERS_API_URL') . $trackingNumbers[$i], [
                    'X-Time-Zone' => 'Asia/Manila',
                ]);
            }
        };

        $pool = new Pool($this->ordersClient, $requests($totalOrders, $trackingNumbers), [
            'fulfilled' => function($response) {
                $orderResponse = json_decode($response->getBody(), true);

                $order = $this->createOrder($orderResponse);

                $this->addToTotalCollections((int)$order->getTotal()->getAmount());

                $this->addToTotalSales(
                    (int)$order->getShippingFee()->getAmount() +
                    (int)$order->getInsuranceFee()->getAmount() +
                    (int)$order->getTransactionFee()->getAmount()
                );

                $this->printOrder($order);
            },
            'rejected' => function($response) {
                echo json_decode($response->getBody(), true);
            },
        ]);

        $promise = $pool->promise();

        $promise->wait();

        $this->displayTotalRevenue();
    }

    /**
     * @param Order $order
     */
    public function printOrder(Order $order)
    {
        echo $order->getTrackingNumber() . ' (' . $order->getStatus() . '):';
        echo "\r\n";
        echo "\t";
        echo 'history:';
        echo "\r\n";
        $sorted = $order->getTurnAroundTime()->sortBy(function($value) {
            return $value['date'];
        });
        $sorted->each(function ($value, $key){
            echo "\t\t" . $value['date'] . ': ' . $key;
            echo "\r\n";
        });
        echo "\t";
        echo 'breakdown:';
        echo "\r\n";
        echo "\t\t" . 'subtotal: ' . number_format($order->getSubTotal()->getAmount() / 100, 2);
        echo "\r\n";
        echo "\t\t" . 'shipping: ' . number_format($order->getShipping()->getAmount() / 100, 2);
        echo "\r\n";
        echo "\t\t" . 'tax: ' . number_format($order->getTax()->getAmount() / 100, 2);
        echo "\r\n";
        echo "\t\t" . 'fee: ' . number_format($order->getFee()->getAmount() / 100, 2);
        echo "\r\n";
        echo "\t\t" . 'insurance: ' . number_format($order->getInsurance()->getAmount() / 100, 2);
        echo "\r\n";
        echo "\t\t" . 'discount: ' . number_format($order->getDiscount()->getAmount() /100, 2);
        echo "\r\n";
        echo "\t\t" . 'total: ' . number_format($order->getTotal()->getAmount() / 100, 2);
        echo "\r\n";
        echo "\t";
        echo 'fees:';
        echo "\r\n";
        echo "\t\t" . 'shipping_fee: ' . number_format($order->getShippingFee()->getAmount() / 100, 2);
        echo "\r\n";
        echo "\t\t" . 'insurance_fee: ' . number_format($order->getInsuranceFee()->getAmount() / 100, 2);
        echo "\r\n";
        echo "\t\t" . 'transaction_fee: ' . number_format($order->getTransactionFee()->getAmount() / 100, 2);
        echo "\r\n\r\n";
    }

    /**
     * @param array $orderResponse
     * @return Order
     */
    private function createOrder(array $orderResponse): Order
    {
        $currency = new Currency('PHP');

        $order = new Order(
            OrderId::fromInt((int)$orderResponse['id']),
            TrackingNumber::fromString($orderResponse['tracking_number']),
            collect($orderResponse['tat']),
            DeliveryStatus::fromString($orderResponse['status']),
            new Money($orderResponse['subtotal'] * 100, $currency),
            new Money($orderResponse['shipping'] * 100, $currency),
            new Money($orderResponse['tax'] * 100, $currency),
            new Money($orderResponse['fee'] * 100, $currency),
            new Money($orderResponse['insurance'] * 100, $currency),
            new Money($orderResponse['discount'] * 100, $currency),
            new Money($orderResponse['total'] * 100, $currency),
            new Money($orderResponse['shipping_fee'] * 100, $currency),
            new Money($orderResponse['insurance_fee'] * 100, $currency),
            new Money($orderResponse['transaction_fee'] * 100, $currency)
        );

        return $order;
    }

    /**
     * @param int $amount
     */
    private function addToTotalCollections(int $amount)
    {
        $this->totalCollection += $amount;
    }

    /**
     * @param int $amount
     */
    private function addToTotalSales(int $amount)
    {
        $this->totalSales += $amount;
    }

    private function displayTotalRevenue()
    {
        echo 'total collections: ' . number_format($this->totalCollection / 100, 2);
        echo "\r\n";
        echo 'total sales: ' . number_format($this->totalSales / 100, 2);
    }
}

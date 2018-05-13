<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Service;

use App\Domain\Entity\Order;
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

    private $totalCollection;

    private $totalSales;

    public function __construct(Client $ordersClient)
    {
        $this->ordersClient = $ordersClient;
    }

    /**
     * @param TrackingNumber $trackingNumber
     * @return string
     */
    public function getOne(TrackingNumber $trackingNumber): string
    {
        $response = $this->ordersClient->request('GET', (string)$trackingNumber, [
            'headers' => [
                'X-Time-Zone' => 'Asia/Manila',
            ]
        ]);

        return $response->getBody();
    }

    /**
     * @param array $trackingNumbers
     */
    public function getMany(array $trackingNumbers)
    {
        $totalOrders = count($trackingNumbers);

        $requests = function ($totalOrders, $trackingNumbers) {
            for ($i = 0; $i < $totalOrders; $i++) {
                yield new Request('GET', 'https://api.staging.lbcx.ph/v1/orders/' . $trackingNumbers[$i], [
                    'headers' => [
                        'X-Time-Zone' => 'Asia/Manila',
                    ]
                ]);
            }
        };

        $pool = new Pool($this->ordersClient, $requests($totalOrders, $trackingNumbers), [
            'fulfilled' => function($response) {
                $orderResponse = json_decode($response->getBody(), true);

                $currency = new Currency('PHP');

                $order = new Order(
                    OrderId::fromInt((int)$orderResponse['id']),
                    TrackingNumber::fromString($orderResponse['tracking_number']),
                    collect($orderResponse['tat']),
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

        echo "\r\n";
        echo 'total collections: ' . $this->totalCollection / 100;
        echo "\r\n";
        echo 'total sales: ' . $this->totalSales / 100;
    }

    /**
     * @param Order $order
     */
    public function printOrder(Order $order)
    {
        echo $order->getTrackingNumber() . ':';
        echo "\r\n";
        echo "\t";
        echo 'history:';
        echo "\r\n";
        $order->getTurnAroundTime()->each(function ($value, $key){
            echo "\t\t" . $value . ': ' . $key;
            echo "\r\n";
        });
        echo "\t";
        echo 'breakdown:';
        echo "\r\n";
        echo "\t\t" . 'subtotal: ' . $order->getSubTotal()->getAmount() / 100;
        echo "\r\n";
        echo "\t\t" . 'shipping: ' . $order->getShipping()->getAmount() / 100;
        echo "\r\n";
        echo "\t\t" . 'tax: ' . $order->getTax()->getAmount() / 100;
        echo "\r\n";
        echo "\t\t" . 'fee: ' . $order->getFee()->getAmount() / 100;
        echo "\r\n";
        echo "\t\t" . 'insurance: ' . $order->getInsurance()->getAmount() / 100;
        echo "\r\n";
        echo "\t\t" . 'discount: ' . $order->getDiscount()->getAmount() /100;
        echo "\r\n";
        echo "\t\t" . 'total: ' . $order->getTotal()->getAmount() / 100;
        echo "\r\n";
        echo "\t";
        echo 'fees:';
        echo "\r\n";
        echo "\t\t" . 'shipping_fee: ' . $order->getShippingFee()->getAmount() / 100;
        echo "\r\n";
        echo "\t\t" . 'insurance_fee: ' . $order->getInsuranceFee()->getAmount() / 100;
        echo "\r\n";
        echo "\t\t" . 'transaction_fee: ' . $order->getTransactionFee()->getAmount() / 100;
        echo "\r\n";
    }

    public function addToTotalCollections(int $amount)
    {
        $this->totalCollection += $amount;
    }

    private function addToTotalSales(int $amount)
    {
        $this->totalSales += $amount;
    }
}

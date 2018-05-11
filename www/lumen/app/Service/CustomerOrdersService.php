<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Service;

use App\Domain\ValueObject\OrderId;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;

class CustomerOrdersService implements OrdersServiceInterface
{
    /**
     * @var Client
     */
    private $ordersClient;

    public function __construct(Client $ordersClient)
    {
        $this->ordersClient = $ordersClient;
    }

    public function getOne(OrderId $orderId): string
    {
        $response = $this->ordersClient->request('GET', (string)$orderId, [
            'headers' => [
                'X-Time-Zone' => 'Asia/Manila',
            ]
        ]);

        return $response->getBody();
    }

    public function getMany(array $orderIds)
    {
        $totalOrders = count($orderIds);

        $requests = function ($totalOrders, $orderIds) {
            for ($i = 0; $i < $totalOrders; $i++) {
                yield new Request('GET', 'https://api.staging.lbcx.ph/v1/orders/' . $orderIds[$i], [
                    'headers' => [
                        'X-Time-Zone' => 'Asia/Manila',
                    ]
                ]);
            }
        };

        $pool = new Pool($this->ordersClient, $requests($totalOrders, $orderIds), [
            'concurrency' => 5,
            'fulfilled' => function($response, $index) {
                echo $response->getBody();
            },
            'rejected' => function($response, $index) {
                echo $response->getBody() . ' => ' . $index;
            },
        ]);

        $promise = $pool->promise();

        $promise->wait();
    }

}

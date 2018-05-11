<?php
/**
 * @author Jediscript <jed.lagunday@gmail.com>
 */

namespace App\Providers;

use App\Service\CustomerOrdersService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class CustomerOrdersServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CustomerOrdersService::class, function () {

            $ordersClient = new Client([
                'base_uri' => 'https://api.staging.lbcx.ph/v1/orders/'
            ]);

            return new CustomerOrdersService($ordersClient);
        });
    }
}

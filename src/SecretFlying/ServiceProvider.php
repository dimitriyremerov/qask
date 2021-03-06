<?php
namespace Qask\SecretFlying;

use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

class ServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['secretFlying.guzzleClient'] = function (Application $app) {
            return new Client();
        };
        $pimple['secretFlying'] = function (Application $app) {
            return new Controller($app['secretFlying.guzzleClient']);
        };
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->get('/secretflying.rss', [$app['secretFlying'], 'getSecretFlyingRss']);
    }
}

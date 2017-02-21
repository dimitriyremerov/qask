<?php
namespace Qask\Visa;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

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
        $pimple['visa.template.index'] = PROJECT_ROOT . '/views/visa.html';
        $pimple['visa.template.result'] = PROJECT_ROOT . '/views/visa_result.html';
        $pimple['visa'] = function () {
            return new Controller();
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
        $app->get('/visa/{nat}/{dest}/{transit}', function (string $nat, string $dest, string $transit) use ($app) : Response {
            if (!$nat) {
                return new Response(file_get_contents($app['visa.template.index']));
            }
            $controller = $app['visa'];
            /* @var $controller Controller */
            $handler = $controller->getResultTemplateHandler($nat, $dest, $transit);
            return new Response($handler(file_get_contents($app['visa.template.result'])));
        });
    }
}

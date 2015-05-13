<?php

namespace CWreden\GitLog\Doctrine;


use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Silex\Application;
use Silex\ServiceProviderInterface;

class DoctrineORMServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $app['orm.proxy.dir'] = __DIR__ . '/../../../../tmp/doctrine/proxy';
        $app['orm.proxy.namespace'] = 'OpenCoders\Podb\Proxies';
        $app['orm.entity.path'] = array(__DIR__ . '/../Entity');

        $app['orm.configuration'] = $app->share(function ($pimple) {
            $configuration = new Configuration();

            $configuration->setProxyDir($pimple['orm.proxy.dir']);
            $configuration->setProxyNamespace($pimple['orm.proxy.namespace']);
            $configuration->setAutoGenerateProxyClasses($pimple['debug']);

            $driverImpl = $configuration->newDefaultAnnotationDriver($pimple['orm.entity.path']);
            $configuration->setMetadataDriverImpl($driverImpl);
            return $configuration;
        });

        $app['orm.event_manager'] = $app->share(function () {
            return new EventManager();
        });

        $app['orm'] = $app->share(function ($pimple) {
            return EntityManager::create(
                $pimple['db'],
                $pimple['orm.configuration'],
                $pimple['orm.event_manager']
            );
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}

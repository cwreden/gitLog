<?php

namespace CWreden\GitLog\GitHub;


use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class OAuthControllerProvider implements ControllerProviderInterface
{

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $app[GitHubServices::GITHUB_OAUTH_CONTROLLER] = $app->share(function ($pimple) {
            return new OAuthController(
                $pimple[GitHubServices::GITHUB_API_CLIENT_ID],
                $pimple[GitHubServices::GITHUB]
            );
        });

        /** @var ControllerCollection $controllerCollection */
        $controllerCollection = $app['controllers_factory'];

        $controllerCollection->match('/sign/in', GitHubServices::GITHUB_OAUTH_CONTROLLER . ':signInAction');
        $controllerCollection->match('/authorize/github', GitHubServices::GITHUB_OAUTH_CONTROLLER . ':authorizeAction');

        return $controllerCollection;
    }
}

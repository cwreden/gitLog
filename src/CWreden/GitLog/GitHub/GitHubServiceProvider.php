<?php

namespace CWreden\GitLog\GitHub;


use Silex\Application;
use Silex\ServiceProviderInterface;

class GitHubServiceProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {
        $app[GitHubServices::GITHUB] = $app->share(function ($pimple) {
            return new GitHub(
                $pimple['session'],
                $pimple[GitHubServices::GITHUB_API_CLIENT_ID],
                $pimple[GitHubServices::GITHUB_API_CLIENT_SECRET],
                ''
            );
        });

        $app[GitHubServices::GITHUB_API] = $app->share(function ($pimple) {
            return new GitHubApi(
                $pimple['session'],
                $pimple[GitHubServices::GITHUB_APP_NAME]
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

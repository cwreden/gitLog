<?php

namespace CWreden\GitLog;


use CWreden\GitLog\GitHub\GitHubServices;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class GitLogControllerProvider implements ControllerProviderInterface
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
        $app[GitLogServices::CONTROLLER] = $app->share(function ($pimple) {
            return new GitLogController(
                $pimple[GitHubServices::GITHUB],
                $pimple[GitHubServices::GITHUB_API]
            );
        });

        /** @var ControllerCollection $collection */
        $collection = $app['controllers_factory'];


        $collection->match('/', GitLogServices::CONTROLLER . ':indexAction');

        $collection->match('/repos', GitLogServices::CONTROLLER . ':reposAction');
        $collection->match('/repos/{user}/{repo}/tags', GitLogServices::CONTROLLER . ':tagsAction');
        $collection->match('/repos/{user}/{repo}/changelog/{tag}', GitLogServices::CONTROLLER . ':changeLogAction');

        return $collection;
    }
}

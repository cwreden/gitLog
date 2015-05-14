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
                $pimple[GitLogServices::APP_NAME],
                $pimple[GitHubServices::GITHUB],
                $pimple[GitHubServices::GITHUB_API],
                $pimple['twig']
            );
        });

        $app[GitLogServices::SEARCH_CONTROLLER] = $app->share(function ($pimple) {
            return new GitLogSearchController(
                $pimple[GitHubServices::GITHUB_API]
            );
        });

        /** @var ControllerCollection $collection */
        $collection = $app['controllers_factory'];


        $collection->get('/', GitLogServices::CONTROLLER . ':indexAction');

        $collection->get('/repos', GitLogServices::CONTROLLER . ':getOwnRepoListAction');
        $collection->get('/users/{owner}/repos', GitLogServices::CONTROLLER . ':getRepoListAction');
        $collection->get('/repos/{owner}/{repo}/commits', GitLogServices::CONTROLLER . ':getCommitListAction');
        $collection->get('/repos/{owner}/{repo}/tags', GitLogServices::CONTROLLER . ':getTagListAction');
        $collection->get('/repos/{owner}/{repo}/tags/{tag}/commits', GitLogServices::CONTROLLER . ':getCommitListForTagAction');
        $collection->get('/changelog/{owner}/{repo}/{tag}', GitLogServices::CONTROLLER . ':getChangeLogAction');

        $collection->get('/search/repositories', GitLogServices::SEARCH_CONTROLLER . ':searchRepositories');

        return $collection;
    }
}

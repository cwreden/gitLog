<?php

namespace CWreden\GitLog;

use CWreden\GitLog\GitHub\GitHub;
use CWreden\GitLog\GitHub\GitHubApi;
use CWreden\GitLog\GitHub\GitHubServiceProvider;
use CWreden\GitLog\GitHub\GitHubServices;
use CWreden\GitLog\GitHub\OAuthControllerProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\SessionServiceProvider;


/**
 * Class Application
 * @package CWreden\GitLog
 */
class Application extends \Silex\Application
{
    /**
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $app = $this;

        $this->register(new SessionServiceProvider());
        $this->register(new ServiceControllerServiceProvider());

        $this->register(new GitHubServiceProvider());
        $this->mount('', new OAuthControllerProvider());

        // TODO Debug
        $this->match('/', function (Request $request) use ($app) {

            /** @var GitHub $gitHub */
            $gitHub = $app[GitHubServices::GITHUB];
            /** @var GitHubApi $gitHubApi */
            $gitHubApi = $app[GitHubServices::GITHUB_API];

            $html = '';

            if($gitHub->isAuthorized()) {
                $user = $gitHubApi->request('/user');

                $html .= '<h3>Logged In</h3>';
                $html .= '<h4>' . $user->name . '</h4>';
                $html .= '<pre>';
                $html .= print_r($user, true);
                $html .= '</pre>';

            } else {
                $html .= '<h3>Not logged in</h3>';
                $html .= '<p><a href="/sign/in">Log In</a></p>';
            }

//            $html .= '<pre>' . print_r($request->getSession()->all(), true) . '</pre>';
            return new Response($html);
        });

        // TODO Load Repositories
        // TODO Load GitTags
        // TODO Load Commits
        // TODO ...
        // TODO Export Services
        // TODO Search Services
        // TODO Report Services
    }
}

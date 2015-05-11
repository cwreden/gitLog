<?php

namespace CWreden\GitLog;

use CWreden\GitLog\GitHub\GitHubServiceProvider;
use CWreden\GitLog\GitHub\OAuthControllerProvider;
use Silex\Provider\ServiceControllerServiceProvider;
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
        $this->mount('', new GitLogControllerProvider());

        // TODO Load Commits
        // TODO ...
        // TODO Export Services
        // TODO Search Services
        // TODO Report Services
    }
}

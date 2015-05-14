<?php

namespace CWreden\GitLog;

use CWreden\GitLog\GitHub\GitHubServiceProvider;
use CWreden\GitLog\GitHub\OAuthControllerProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;


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

        $this->register(new SessionServiceProvider());
        $this->register(new ServiceControllerServiceProvider());
        $this->register(new TwigServiceProvider());
        $this->register(new DoctrineServiceProvider());
        $this->register(new UrlGeneratorServiceProvider());

        $this->register(new GitHubServiceProvider());
        $this->mount('', new OAuthControllerProvider());
        $this->mount('', new GitLogControllerProvider());

        // TODO Create ChangeLog
        // TODO Edit/Ignore commit messages

        // TODO Export Services

        // TODO Search Services
        // TODO Report Services

        foreach ($values as $key => $value) {
            $this[$key] = $value;
        }
    }
}

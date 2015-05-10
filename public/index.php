<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CWreden\GitLog\Application;

$config = include __DIR__ . '/../config/gitlog.conf.php';

$app = new Application($config);
$app->run();

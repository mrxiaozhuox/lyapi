<?php


require 'init.php';

\LyApi\LyApi::output();

/* */
$whoops = new \Whoops\Run();
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
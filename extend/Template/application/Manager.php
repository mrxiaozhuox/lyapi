<?php

namespace Extend\Template\application;

use Application\Foundation\ApiCon;
use LyApi\Core\Request;
use LyApi\Core\Response;

class Manager extends ApiCon
{
    public function index(Request $req, Response $resp)
    {
        return ['OK'];
    }
}

<?php

/**
 * LyApi Framework 演示程序
 */

namespace Application\Controller;

use Application\Foundation\ViewCon;
use Common\Encryption;
use LyApi\Core\Request;
use LyApi\Core\Response;
use LyApi\Core\View;

class Main extends ViewCon
{

    /**
     * 主页面渲染程序
     */
    public function index(Request $req, Response $resp)
    {
        return View::render('index', []);
    }
}

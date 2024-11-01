<?php

/**
 * LyApi Framework 演示程序
 */

namespace Application\Controller;

use Application\Interface\View as ViewController;
use LyApi\Core\Request;
use LyApi\Core\Response;
use LyApi\Core\View;

class Main extends ViewController
{
    /**
     * 主页面渲染程序
     */
    public function index(Request $req, Response $resp)
    {
        return View::render('index', []);
    }
}

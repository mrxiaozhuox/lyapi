<?php

namespace Application\Interface;

use LyApi\Interface\Controller;

class View extends Controller
{
    public function _export($data)
    {
        $data = parent::_export($data);

        return $data;
    }
}

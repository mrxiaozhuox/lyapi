<?php

namespace Application\Interface;

use LyApi\Core\View;
use LyApi\Foundation\Controller;

use const Application\Config\API_STRUCTURE_INFO;

class Api extends Controller
{
    public function _export($data)
    {
        $options = [];

        if (is_array($data)) {
            if (array_key_exists("_opt", $data)) {
                $options = $data['_opt'];
            }
            unset($data['_opt']);
        }

        $data = parent::_export($data);

        return View::api($data, API_STRUCTURE_INFO, $options);
    }
}

<?php

namespace Application\Foundation;

use LyApi\Core\View;
use LyApi\Foundation\Bcontrol;

use const Application\Config\API_STRUCTURE_INFO;

class ApiCon extends Bcontrol
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

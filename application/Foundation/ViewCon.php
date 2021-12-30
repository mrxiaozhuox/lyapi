<?php

namespace Application\Foundation;

use LyApi\Foundation\Bcontrol;

class ViewCon extends Bcontrol
{
    public function _export($data)
    {
        $data = parent::_export($data);

        return $data;
    }
}

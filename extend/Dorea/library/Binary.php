<?php

namespace Extend\Dorea\library;

class Binary
{
    private $inner = [];

    public function __construct($bin)
    {
        $this->inner = $bin;
    }

    // TODO: 解决方案暂时没有找到，这里鸽一下：QWQ
    public function stringify(): string
    {
        return "";
    }

    public function toArray(): array
    {
        return $this->inner;
    }

    // TODO: 这里也是未完成的代码
    public static function fromString($str): Binary
    {
        return new Binary([]);
    }

}
<?php

namespace Extend\Dorea\library;

class Tuple
{
    private $items = [];

    public function __construct($f, $s)
    {
        $this->items = [$f, $s];
    }

    public function first($to) {
        if ($to == null) {
            return $this->items[0];
        }
        $this->items[0] = $to;
        return $to;
    }

    public function second($to) {
        if ($to == null) {
            return $this->items[1];
        }
        $this->items[1] = $to;
        return $to;
    }

    public function __serialize(): array
    {
        return [$this->first(null), $this->second(null)];
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public static function build($f, $s): Tuple
    {
        return new self($f, $s);
    }

}
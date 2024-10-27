<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 数据结构：队列 - Queue
// 队列的特性及其用途：
// 栈为 先进先出 (FIFO) 的数据类型。
// 名为队列的原因为它像排队一样，先进先出。
// -+----------------------------+-

namespace LyApi\Support\DataStruct;

class Queue
{
    private $queueArr = [];
    private $queueIdx = -1;

    public function push(...$values)
    {
        foreach ($values as $value) {
            array_push($this->queueArr, $value);
            $this->queueIdx++; // 将队列长度 ++
        }
    }

    public function pop()
    {
        if ($this->queueIdx < 0) {
            return false;
        }

        array_shift($this->queueArr);
        $this->queueIdx--;

        return true;
    }

    public function front()
    {
        return $this->queueArr[0];
    }

    public function empty()
    {
        return $this->queueIdx < 0;
    }

    public function length()
    {
        return $this->queueIdx + 1;
    }

    /**
     * 快捷获得队列对象函数
     */
    public static function create()
    {
        return new self();
    }
}

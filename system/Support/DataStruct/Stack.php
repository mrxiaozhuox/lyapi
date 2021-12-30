<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 数据结构：栈 - Stack
// 栈的特性及其用途：
// 栈为 后进先出 (LIFO) 的数据类型。
// 先进入的数据会被压至栈底，新数据会在旧数据之上进行增加。
// 出栈时会从栈顶取走数据，则为最新入栈的数据会被先取走。
// -+----------------------------+-

namespace LyApi\Support\DataStruct;


class Stack
{
    private $stackArr = [];
    private $stackIdx = -1;

    // 数据入栈
    public function push(...$values)
    {
        foreach ($values as $value) {
            array_push($this->stackArr, $value);
            $this->stackIdx++; // 将栈顶 ++
        }
    }

    // 数据出栈
    public function pop()
    {
        // 如果为空栈则不做操作
        if ($this->stackIdx < 0) return false;

        array_pop($this->stackArr); // 弹出数据
        $this->stackIdx--; // 栈顶减少一位
        
        return true;
    }

    // 取得栈顶元素
    public function top()
    {
        return $this->stackArr[$this->stackIdx];
    }

    // 栈是否为空
    public function empty()
    {
        return $this->stackIdx < 0;
    }

    /**
     * 快捷获得栈对象函数
     */
    public static function create()
    {
        return new self();
    }
}

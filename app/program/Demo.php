<?php

namespace APP\program;

class Demo
{
    // Demo: 加函数
    public function Add($num1, $num2)
    {
        return $num1 + $num2;
    }

    // Demo: 减函数
    public function Reduce($num1, $num2)
    {
        return $num1 - $num2;
    }

    // Demo: 测试函数
    public function Test()
    {
        return "This is Test Funciton in Demo";
    }
}

<?php

namespace APP\program;

class Demo
{
    public function Add($num1, $num2)
    {
        return $num1 + $num2;
    }

    public function Reduce($num1, $num2)
    {
        return $num1 - $num2;
    }

    public function Test()
    {
        return "This is Test Funciton in Demo";
    }
}

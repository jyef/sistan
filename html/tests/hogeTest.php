<?php

use PHPUnit\Framework\TestCase;

class HogeTest extends TestCase
{
    public function testMinimumViableTest()
    {
        $this->assertTrue(false, "falseはtrueではない");
    }
}

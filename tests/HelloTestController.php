<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use frontend\controllers\HelloController;

class HelloTestContrller extends TestCase
{
    public function testSame(): void
    {
        $this->assertSame(
            'aaab',
            (new HelloController('app-frontend', []))->getVaule('aaab')
        );
    }
}

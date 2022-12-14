<?php

namespace hxv\AoC\Tests;

use PHPUnit\Framework\TestCase;

class EmptyTest extends TestCase
{
    public function testCheckIfPHPUnitWorks(): void
    {
        self::expectNotToPerformAssertions();
    }
}

<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class TokenTypeTest extends TestCase
{
    public function testAssertEquals(): void
    {
        $this->assertEquals(\Aegis\Token\TokenType::T_CLOSING_TAG, 2);
    }
}
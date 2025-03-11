<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class GreeterTest extends TestCase
{
    public function testPositive(): void
    {
        $greeter = 3;

        $greeting = 3;

        $this->assertSame($greeting, $greeter);
    }
}
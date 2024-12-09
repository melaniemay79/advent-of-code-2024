<?php

namespace Tests\Unit;

use AdventOfCode2024\DayTwelve;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayTwelveTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_12.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwelve = new DayTwelve($this->file);

        $reflection = new \ReflectionClass($dayTwelve);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwelve);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayTwelve('nonexistent.txt');
    }
}

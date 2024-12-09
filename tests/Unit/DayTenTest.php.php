<?php

namespace Tests\Unit;

use AdventOfCode2024\DayTen;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayTenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_10.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTen = new DayTen($this->file);

        $reflection = new \ReflectionClass($dayTen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayTen('nonexistent.txt');
    }
}

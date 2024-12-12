<?php

namespace Tests\Unit;

use AdventOfCode2024\DayFifteen;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayFifteenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_15.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayFifteen = new DayFifteen($this->file);

        $reflection = new \ReflectionClass($dayFifteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayFifteen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayFifteen('nonexistent.txt');
    }
}

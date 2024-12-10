<?php

namespace Tests\Unit;

use AdventOfCode2024\DayNine;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayNineTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_09.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayNine = new DayNine($this->file);

        $reflection = new \ReflectionClass($dayNine);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayNine);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayNine('nonexistent.txt');
    }
}
<?php

namespace Tests\Unit;

use AdventOfCode2024\Day14;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day14Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_14.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayFourteen = new Day14($this->file);

        $reflection = new \ReflectionClass($dayFourteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayFourteen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day14('nonexistent.txt');
    }
}

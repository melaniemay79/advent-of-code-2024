<?php

namespace Tests\Unit;

use AdventOfCode2024\Day25;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day25Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_25.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyFive = new Day25($this->file);

        $reflection = new \ReflectionClass($dayTwentyFive);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwentyFive);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day25('nonexistent.txt');
    }
}

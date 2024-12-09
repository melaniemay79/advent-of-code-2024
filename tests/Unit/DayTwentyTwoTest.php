<?php

namespace Tests\Unit;

use AdventOfCode2024\DayTwentyTwo;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayTwentyTwoTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_22.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyTwo = new DayTwentyTwo($this->file);

        $reflection = new \ReflectionClass($dayTwentyTwo);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwentyTwo);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayTwentyTwo('nonexistent.txt');
    }
}

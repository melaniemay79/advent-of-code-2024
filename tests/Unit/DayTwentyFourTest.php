<?php

namespace Tests\Unit;

use AdventOfCode2024\DayTwentyFour;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayTwentyFourTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_24.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyFour = new DayTwentyFour($this->file);

        $reflection = new \ReflectionClass($dayTwentyFour);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwentyFour);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayTwentyFour('nonexistent.txt');
    }
}

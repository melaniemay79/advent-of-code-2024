<?php

namespace Tests\Unit;

use AdventOfCode2024\DayTwentyThree;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayTwentyThreeTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_23.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyThree = new DayTwentyThree($this->file);

        $reflection = new \ReflectionClass($dayTwentyThree);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwentyThree);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayTwentyThree('nonexistent.txt');
    }
}

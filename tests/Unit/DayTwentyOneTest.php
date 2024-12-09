<?php

namespace Tests\Unit;

use AdventOfCode2024\DayTwentyOne;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayTwentyOneTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_21.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyOne = new DayTwentyOne($this->file);

        $reflection = new \ReflectionClass($dayTwentyOne);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwentyOne);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayTwentyOne('nonexistent.txt');
    }
}

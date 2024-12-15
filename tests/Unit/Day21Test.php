<?php

namespace Tests\Unit;

use AdventOfCode2024\Day21;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day21Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_21.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyOne = new Day21($this->file);

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

        new Day21('nonexistent.txt');
    }
}

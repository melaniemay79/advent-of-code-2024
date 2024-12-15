<?php

namespace Tests\Unit;

use AdventOfCode2024\Day20;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day20Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_20.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwenty = new Day20($this->file);

        $reflection = new \ReflectionClass($dayTwenty);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwenty);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day20('nonexistent.txt');
    }
}

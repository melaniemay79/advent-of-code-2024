<?php

namespace Tests\Unit;

use AdventOfCode2024\Day12;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day12Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_12.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwelve = new Day12($this->file);

        $reflection = new \ReflectionClass($dayTwelve);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwelve);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day12('nonexistent.txt');
    }
}

<?php

namespace Tests\Unit;

use AdventOfCode2024\Day13;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day13Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_13.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayThirteen = new Day13($this->file);

        $reflection = new \ReflectionClass($dayThirteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayThirteen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day13('nonexistent.txt');
    }
}

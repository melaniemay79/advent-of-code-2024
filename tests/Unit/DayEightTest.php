<?php

namespace Tests\Unit;

use AdventOfCode2024\DayEight;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayEightTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_08.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayEight = new DayEight($this->file);

        $reflection = new \ReflectionClass($dayEight);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayEight);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayEight('nonexistent.txt');
    }
}

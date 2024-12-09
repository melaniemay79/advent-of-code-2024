<?php

namespace Tests\Unit;

use AdventOfCode2024\DayTwenty;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayTwentyTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_20.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwenty = new DayTwenty($this->file);

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

        new DayTwenty('nonexistent.txt');
    }
}

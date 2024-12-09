<?php

namespace Tests\Unit;

use AdventOfCode2024\DayFourteen;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayFourteenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_14.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayFourteen = new DayFourteen($this->file);

        $reflection = new \ReflectionClass($dayFourteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayFourteen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayFourteen('nonexistent.txt');
    }
}

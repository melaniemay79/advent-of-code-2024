<?php

namespace Tests\Unit;

use AdventOfCode2024\DayEighteen;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayEighteenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_18.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayEighteen = new DayEighteen($this->file);

        $reflection = new \ReflectionClass($dayEighteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayEighteen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayEighteen('nonexistent.txt');
    }
}

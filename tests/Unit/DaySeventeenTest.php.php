<?php

namespace Tests\Unit;

use AdventOfCode2024\DaySeventeen;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DaySeventeenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_17.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $daySeventeen = new DaySeventeen($this->file);

        $reflection = new \ReflectionClass($daySeventeen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($daySeventeen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DaySeventeen('nonexistent.txt');
    }
}

<?php

namespace Tests\Unit;

use AdventOfCode2024\DaySixteen;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DaySevenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_16.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $daySixteen = new DaySixteen($this->file);

        $reflection = new \ReflectionClass($daySixteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($daySixteen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DaySixteen('nonexistent.txt');
    }
}

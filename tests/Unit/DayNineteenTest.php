<?php

namespace Tests\Unit;

use AdventOfCode2024\DayNineteen;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayNineteenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_19.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayNineteen = new DayNineteen($this->file);

        $reflection = new \ReflectionClass($dayNineteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayNineteen);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayNineteen('nonexistent.txt');
    }
}

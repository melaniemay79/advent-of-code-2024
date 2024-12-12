<?php

namespace Tests\Unit;

use AdventOfCode2024\DayEleven;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayElevenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_11.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayEleven = new DayEleven($this->file);

        $reflection = new \ReflectionClass($dayEleven);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayEleven);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayEleven('nonexistent.txt');
    }
}

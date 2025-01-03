<?php

namespace Tests\Unit;

use AdventOfCode2024\Day11;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day11Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_11.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayEleven = new Day11($this->file);

        $reflection = new \ReflectionClass($dayEleven);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayEleven);

        $expected = '125 17';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day11('nonexistent.txt');
    }

    public function test_25_blinks_returns_correct_result(): void
    {
        $dayEleven = new Day11($this->file);

        $result = $dayEleven->simulateBlinks(25);

        $this->assertEquals(55312, $result);
    }
}

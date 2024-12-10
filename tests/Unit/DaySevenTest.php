<?php

namespace Tests\Unit;

use AdventOfCode2024\DaySeven;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DaySevenTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_07.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $daySeven = new DaySeven($this->file);

        $reflection = new \ReflectionClass($daySeven);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($daySeven);

        $expected = '190: 10 19
3267: 81 40 27
83: 17 5
156: 15 6
7290: 6 8 6 15
161011: 16 10 13
192: 17 8 14
21037: 9 7 18 13
292: 11 6 16 20';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DaySeven('nonexistent.txt');
    }

    public function test_find_combination_returns_correct_sum(): void
    {
        $daySeven = new DaySeven($this->file);

        $result = $daySeven->getResult();

        $this->assertEquals(3749, $result);
    }

    public function test_find_combination_returns_correct_sum_with_concatenation(): void
    {
        $daySeven = new DaySeven($this->file);

        $result = $daySeven->getResult(true);

        $this->assertEquals(11387, $result);
    }
}

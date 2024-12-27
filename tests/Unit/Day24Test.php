<?php

namespace Tests\Unit;

use AdventOfCode2024\Day24;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day24Test extends TestCase
{
    private string $file;

    private string $file2;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_24.txt';
        $this->file2 = __DIR__.'/../data/input_24_part_2.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyFour = new Day24($this->file);

        $reflection = new \ReflectionClass($dayTwentyFour);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwentyFour);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day24('nonexistent.txt');
    }

    public function test_part_one_returns_correct_value(): void
    {
        $dayTwentyFour = new Day24($this->file);

        $this->assertEquals(2024, $dayTwentyFour->part1());
    }

    public function test_part_two_returns_correct_value(): void
    {
        $dayTwentyFour = new Day24($this->file2);
        $expected = 'z00,z01,z02,z03,z04';
        $this->assertEquals($expected, $dayTwentyFour->part2());
    }
}

<?php

namespace Tests\Unit;

use AdventOfCode2024\Day23;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day23Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_23.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyThree = new Day23($this->file);

        $reflection = new \ReflectionClass($dayTwentyThree);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTwentyThree);

        $expected = '';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day23('nonexistent.txt');
    }

    public function test_part_one_returns_correct_value(): void
    {
        $dayTwentyThree = new Day23($this->file);

        $this->assertEquals(7, $dayTwentyThree->part1());
    }

    public function test_part_two_returns_correct_value(): void
    {
        $dayTwentyThree = new Day23($this->file);
        $expected = 'co,de,ka,ta';

        $this->assertEquals($expected, $dayTwentyThree->part2());
    }
}

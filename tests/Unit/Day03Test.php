<?php

namespace Tests\Unit;

use AdventOfCode2024\Day03;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day03Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_03.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayThree = new Day03($this->file);

        $reflection = new \ReflectionClass($dayThree);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayThree);

        $expected = "xmul(2,4)%&mul[3,7]!@^do_not_mul(5,5)+mul(32,64]then(mul(11,8)mul(8,5))xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))";

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day03('nonexistent.txt');
    }

    public function test_calculate_sum_part_one_as_expected(): void
    {
        $dayThree = new Day03($this->file);

        /*
        Expected:
        mul(2,4) = 8
        mul(5,5) = 25
        mul(11,8) = 88
        mul(8,5) = 40
        mul(2,4) = 8
        mul(5,5) = 25
        mul(11,8) = 88
        mul(8,5) = 40
        */

        $expected = 8 + 25 + 88 + 40 + 8 + 25 + 88 + 40;

        $this->assertEquals($expected, $dayThree->calculateSumPartOne());
    }

    public function test_calculate_sum_part_two_as_expected(): void
    {
        $dayThree = new Day03($this->file);

        /*
        Expected:
        mul(2,4) = 8
        mul(5,5) = 25
        mul(11,8) = 88
        mul(8,5) = 40
        mul(2,4) = 8
        mul(8,5) = 40
        */

        $expected = 8 + 25 + 88 + 40 + 8 + 40;

        $this->assertEquals($expected, $dayThree->calculateSumPartTwo());
    }
}

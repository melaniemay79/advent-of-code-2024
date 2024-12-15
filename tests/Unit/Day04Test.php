<?php

namespace Tests\Unit;

use AdventOfCode2024\Day04;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day04Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_04.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayFour = new Day04($this->file);

        $reflection = new \ReflectionClass($dayFour);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayFour);

        $expected = 'MMMSXXMASM
MSAMXMSMSA
AMXSXMAAMM
MSAMASMSMX
XMASAMXAMM
XXAMMXXAMA
SMSMSASXSS
SAXAMASAAA
MAMMMXMMMM
MXMXAXMASX';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day04('nonexistent.txt');
    }

    public function test_calculate_sum_part_one_as_expected(): void
    {
        $dayFour = new Day04($this->file);

        $this->assertEquals(18, $dayFour->find());
    }

    public function test_calculate_sum_part_two_as_expected(): void
    {
        $dayFour = new Day04($this->file);

        $this->assertEquals(9, $dayFour->findX());
    }
}

<?php

namespace Tests\Unit;

use AdventOfCode2024\DayTwo;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayTwoTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_02.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwo = new DayTwo($this->file);

        $reflection = new \ReflectionClass($dayTwo);
        $reportProperty = $reflection->getProperty('report');
        $reportProperty->setAccessible(true);
        $report = $reportProperty->getValue($dayTwo);

        $expected = [
            [7, 6, 4, 2, 1],
            [1, 2, 7, 8, 9],
            [9, 7, 6, 2, 1],
            [1, 3, 2, 4, 5],
            [8, 6, 4, 4, 1],
            [1, 3, 6, 7, 9],
        ];

        $this->assertEquals($expected, $report);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayTwo('nonexistent.txt');
    }

    public function test_calculate_sum_safe_without_sequence_returns_as_expected(): void
    {
        $dayTwo = new DayTwo($this->file);

        /*
        Expected: 2
        7 6 4 2 1: Safe because the levels are all decreasing by 1 or 2.
        1 2 7 8 9: Unsafe because 2 7 is an increase of 5.
        9 7 6 2 1: Unsafe because 6 2 is a decrease of 4.
        1 3 2 4 5: Unsafe because 1 3 is increasing but 3 2 is decreasing.
        8 6 4 4 1: Unsafe because 4 4 is neither an increase or a decrease.
        1 3 6 7 9: Safe because the levels are all increasing by 1, 2, or 3.
        */

        $this->assertEquals(2, $dayTwo->calculateSafe());
    }

    public function test_calculate_sum_safe_with_sequence_returns_as_expected(): void
    {
        $dayTwo = new DayTwo($this->file);

        /*
        Expected: 4
        7 6 4 2 1: Safe without removing any level.
        1 2 7 8 9: Unsafe regardless of which level is removed.
        9 7 6 2 1: Unsafe regardless of which level is removed.
        1 3 2 4 5: Safe by removing the second level, 3.
        8 6 4 4 1: Safe by removing the third level, 4.
        1 3 6 7 9: Safe without removing any level.
        */

        $this->assertEquals(4, $dayTwo->calculateSafe(true));
    }
}

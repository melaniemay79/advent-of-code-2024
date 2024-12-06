<?php

namespace Tests\Unit;

use AdventOfCode2024\DayOne;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayOneTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/data/input_01.txt';
    }

    public function test_constructor_processes_and_sorts_input_correctly(): void
    {
        $dayOne = new DayOne($this->file);

        $reflection = new \ReflectionClass($dayOne);
        $set1Property = $reflection->getProperty('set1');
        $set1Property->setAccessible(true);
        $set1 = $set1Property->getValue($dayOne);

        $set2Property = $reflection->getProperty('set2');
        $set2Property->setAccessible(true);
        $set2 = $set2Property->getValue($dayOne);

        $this->assertEquals(['100', '200', '250', '300', '300', '1200'], $set1);
        $this->assertEquals(['100', '100', '300', '300', '300', '900'], $set2);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayOne('nonexistent.txt');
    }

    public function test_calculate_sum_part_one_returns_correct_sum(): void
    {
        $dayOne = new DayOne($this->file);

        /*
        Expected:
        100 - 100 = 0
        200 - 100 = 100
        300 - 250 = 50
        300 - 300 = 0
        300 - 300 = 0
        1200 - 900 = 300

        0 + 100 + 50 + 0 + 0 + 300 = 450
        */

        $sumOfDiffs = (100 - 100) + (200 - 100) + (300 - 250) + (300 - 300) + (300 - 300) + (1200 - 900);
        $this->assertEquals($sumOfDiffs, $dayOne->calculateSumPartOne());
    }

    public function test_calculate_sum_part_two_returns_correct_sum(): void
    {
        $dayOne = new DayOne($this->file);

        /*
        Expected:
        100 * 2 = 200
        200 * 0 = 0
        300 * 2 = 600
        300 * 2 = 600
        300 * 2 = 600
        1200 * 0 = 0

        100 + 0 + 600 + 600 + 600 + 0 = 1900
        */

        $sumOfDiffs = (100 * 2) + (200 * 0) + (300 * 2) + (300 * 2) + (300 * 2) + (1200 * 0);
        $this->assertEquals($sumOfDiffs, $dayOne->calculateSumPartTwo());
    }
}

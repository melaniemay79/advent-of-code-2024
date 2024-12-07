<?php

namespace Tests\Unit;

use AdventOfCode2024\DayFive;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DayFiveTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_05.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayFive = new DayFive($this->file);

        $reflection = new \ReflectionClass($dayFive);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayFive);

        $expected = '47|53
97|13
97|61
97|47
75|29
61|13
75|53
29|13
97|29
53|29
61|53
97|53
61|29
47|13
75|47
97|75
47|61
75|61
47|29
75|13
53|13

75,47,61,53,29
97,61,53,29,13
75,29,13
75,97,47,61,53
61,13,29
97,13,75,29,47';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DayFive('nonexistent.txt');
    }

    public function test_calculate_sum_part_one_as_expected(): void
    {
        $dayFive = new DayFive($this->file);

        $this->assertEquals(143, $dayFive->getSum());
    }

    public function test_calculate_sum_part_two_as_expected(): void
    {
        $dayFive = new DayFive($this->file);

        $this->assertEquals(123, $dayFive->getCorrectedSum());
    }
}

<?php

namespace Tests\Unit;

use AdventOfCode2024\Day19;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day19Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_19.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayNineteen = new Day19($this->file);

        $reflection = new \ReflectionClass($dayNineteen);
        $patternsProperty = $reflection->getProperty('patterns');
        $patternsProperty->setAccessible(true);
        $patterns = $patternsProperty->getValue($dayNineteen);
        $expectedPatterns = ['r', 'wr', 'b', 'g', 'bwu', 'rb', 'gb', 'br'];
        $this->assertEquals($expectedPatterns, $patterns);

        $designsProperty = $reflection->getProperty('designs');
        $designsProperty->setAccessible(true);
        $designs = $designsProperty->getValue($dayNineteen);
        $expectedDesigns = ['brwrr', 'bggr', 'gbbr', 'rrbgbr', 'ubwu', 'bwurrg', 'brgr', 'bbrgwb'];
        $this->assertEquals($expectedDesigns, $designs);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day19('nonexistent.txt');
    }

    public function test_solve_part_one_returns_correct_result(): void
    {
        $dayNineteen = new Day19($this->file);
        $this->assertEquals(6, $dayNineteen->solvePart1());
    }

    public function test_solve_part_two_returns_correct_result(): void
    {
        $dayNineteen = new Day19($this->file);
        $this->assertEquals(16, $dayNineteen->solvePart2());
    }
}

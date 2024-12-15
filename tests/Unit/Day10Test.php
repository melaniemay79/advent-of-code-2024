<?php

namespace Tests\Unit;

use AdventOfCode2024\Day10;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day10Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_10.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTen = new Day10($this->file);

        $reflection = new \ReflectionClass($dayTen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayTen);

        $expected = [
            '89010123',
            '78121874',
            '87430965',
            '96549874',
            '45678903',
            '32019012',
            '01329801',
            '10456732',
        ];

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day10('nonexistent.txt');
    }

    public function test_calculate_trailhead_scores_returns_correct_score(): void
    {
        $dayTen = new Day10($this->file);

        $totalScore = $dayTen->calculateTrailheadScores();

        $this->assertEquals(36, $totalScore);
    }
}

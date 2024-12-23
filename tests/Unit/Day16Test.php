<?php

namespace Tests\Unit;

use AdventOfCode2024\Day16;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day16Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_16.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $daySixteen = new Day16($this->file);

        $reflection = new \ReflectionClass($daySixteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($daySixteen);

        $expected = '###############
#.......#....E#
#.#.###.#.###.#
#.....#.#...#.#
#.###.#####.#.#
#.#.#.......#.#
#.#.#####.###.#
#...........#.#
###.#.#####.#.#
#...#.....#.#.#
#.#.#.###.#.#.#
#.....#...#.#.#
#.###.#.#.#.#.#
#S..#.....#...#
###############';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day16('nonexistent.txt');
    }

    public function test_solve_max_returns_correct_result(): void
    {
        $daySixteen = new Day16($this->file);
        $this->assertEquals(11048, $daySixteen->solve()['part1']);
    }

    public function test_solve_part2_returns_correct_result(): void
    {
        $daySixteen = new Day16($this->file);
        $this->assertEquals(64, $daySixteen->solve()['part2']);
    }
}

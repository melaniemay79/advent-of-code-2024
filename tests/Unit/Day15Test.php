<?php

namespace Tests\Unit;

use AdventOfCode2024\Day15;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day15Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_15.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayFifteen = new Day15($this->file);

        $reflection = new \ReflectionClass($dayFifteen);
        $gridProperty = $reflection->getProperty('grid');
        $gridProperty->setAccessible(true);
        $grid = $gridProperty->getValue($dayFifteen);

        $expectedGrid = '##########
#..O..O.O#
#......O.#
#.OO..O.O#
#..O@..O.#
#O#..O...#
#O..O..O.#
#.OO.O.OO#
#....O...#
##########';
        $expectedGrid = explode("\n", $expectedGrid);
        $expectedGrid = array_map(fn ($line) => str_split($line), $expectedGrid);

        $movesProperty = $reflection->getProperty('moves');
        $movesProperty->setAccessible(true);
        $moves = $movesProperty->getValue($dayFifteen);

        $expectedMoves = '<vv>^<v^>v>^vv^v>v<>v^v<v<^vv<<<^><<><>>v<vvv<>^v^>^<<<><<v<<<v^vv^v>^
vvv<<^>^v^^><<>>><>^<<><^vv^^<>vvv<>><^^v>^>vv<>v<<<<v<^v>^<^^>>>^<v<v
><>vv>v^v^<>><>>>><^^>vv>v<^^^>>v^v^<^^>v^^>v^<^v>v<>>v^v^<v>v^^<^^vv<
<<v<^>>^^^^>>>v^<>vvv^><v<<<>^^^vv^<vvv>^>v<^^^^v<>^>vvvv><>>v^<<^^^^^
^><^><>>><>^^<<^^v>>><^<v>^<vv>>v>>>^v><>^v><<<<v>>v<v<v>vvv>^<><<>^><
^>><>^v<><^vvv<^^<><v<<<<<><^v<<<><<<^^<v<^^^><^>>^<v^><<<^>>^v<v^v<v^
>^>>^v>vv>^<<^v<>><<><<v<<v><>v<^vv<<<>^^v^>^^>>><<^v>>v^v><^^>>^<>vv^
<><^^>^^^<><vvvvv^v<v<<>^v<v>v<<^><<><<><<<^^<<<^<<>><<><^^^>^^<>^>v<>
^^>vv<^v^v<vv>^<><v<^v>^^^>>>^^vvv^>vvv<>>>^<^>>>>>^<<^v>^vvv<>^<><<v>
v^^>>><<^^<>>^v^<v^vv<>v^<<>^<^v^v><^<<<><<^<v><v<>vv>>v><v^<vv<>v^<<^';

        $expectedMoves = str_split(str_replace("\n", '', $expectedMoves));

        $robotXProperty = $reflection->getProperty('robotX');
        $robotXProperty->setAccessible(true);
        $robotX = $robotXProperty->getValue($dayFifteen);

        $robotYProperty = $reflection->getProperty('robotY');
        $robotYProperty->setAccessible(true);
        $robotY = $robotYProperty->getValue($dayFifteen);

        $this->assertEquals($expectedGrid, $grid);
        $this->assertEquals($expectedMoves, $moves);
        $this->assertEquals(4, $robotX);
        $this->assertEquals(4, $robotY);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day15('nonexistent.txt');
    }

    public function test_solve_part1_returns_correct_result(): void
    {
        $dayFifteen = new Day15($this->file);
        $this->assertEquals(10092, $dayFifteen->solve());
    }

    public function test_solve_part2_returns_correct_result(): void
    {
        $dayFifteen = new Day15($this->file);
        $this->assertEquals(9021, $dayFifteen->solve(true));
    }
}

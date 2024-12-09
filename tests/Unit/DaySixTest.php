<?php

namespace Tests\Unit;

use AdventOfCode2024\DaySix;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DaySixTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_06.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $daySix = new DaySix($this->file);

        $reflection = new \ReflectionClass($daySix);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($daySix);

        $expected = '....#.....
.........#
..........
..#.......
.......#..
..........
.#..^.....
........#.
#.........
......#...';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new DaySix('nonexistent.txt');
    }

    public function test_predict_guard_positions_returns_as_expected(): void
    {
        $daySix = new DaySix($this->file);

        $guardPositions = $daySix->predictGuardMovements();

        $expectedOutput = [
            '0' => '....#.....',
            '1' => '....XXXXX#',
            '2' => '....X...X.',
            '3' => '..#.X...X.',
            '4' => '..XXXXX#X.',
            '5' => '..X.X.X.X.',
            '6' => '.#XXXXXXX.',
            '7' => '.XXXXXXX#.',
            '8' => '#XXXXXXX..',
            '9' => '......#X..',
        ];

        $this->assertEquals(41, $guardPositions['guardPositions']);
        $this->assertEquals($expectedOutput, $guardPositions['output']);
    }

    public function test_find_obstacles_returns_as_expected(): void
    {
        $daySix = new DaySix($this->file);

        $obstacles = $daySix->createInfiniteLoops();
        $this->assertEquals(6, $obstacles);
    }
}

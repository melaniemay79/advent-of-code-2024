<?php

namespace Tests\Unit;

use AdventOfCode2024\Day18;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day18Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_18.txt';
    }

    public function test_constructor_processes_grid_correctly(): void
    {
        $gridSize = 7;
        $maxBytes = 12;
        $dayEighteen = new Day18($this->file, $gridSize, $maxBytes);

        $reflection = new \ReflectionClass($dayEighteen);
        $gridProperty = $reflection->getProperty('grid');
        $gridProperty->setAccessible(true);

        /** @var array<int, array<int, string>> $grid */
        $grid = $gridProperty->getValue($dayEighteen);

        $this->assertEquals('.', $grid[0][0]);
        $this->assertEquals('#', $grid[0][3]);
        $this->assertEquals('.', $grid[$gridSize - 1][$gridSize - 1]);
    }

    public function test_constructor_processes_bytes_correctly(): void
    {
        $gridSize = 7;
        $maxBytes = 12;
        $dayEighteen = new Day18($this->file, $gridSize, $maxBytes);

        $reflection = new \ReflectionClass($dayEighteen);
        $bytesProperty = $reflection->getProperty('bytes');
        $bytesProperty->setAccessible(true);
        $bytes = $bytesProperty->getValue($dayEighteen);

        $expected = [
            [5, 4],
            [4, 2],
            [4, 5],
            [0, 3],
            [2, 4],
            [1, 5],
            [3, 3],
            [5, 1],
            [1, 2],
            [3, 6],
            [6, 0],
            [6, 2],
            [5, 5],
            [2, 5],
            [6, 5],
            [1, 4],
            [0, 4],
            [6, 4],
            [1, 1],
            [6, 1],
            [1, 0],
            [0, 5],
            [1, 6],
            [2, 0],
        ];

        $this->assertEquals($expected, $bytes);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day18('nonexistent.txt', 7, 12);
    }

    public function test_find_shortest_path_returns_correct_value(): void
    {
        $gridSize = 7;
        $maxBytes = 12;
        $dayEighteen = new Day18($this->file, $gridSize, $maxBytes);

        $this->assertEquals(22, $dayEighteen->findShortestPath());
    }
}

<?php

namespace Tests\Unit;

use AdventOfCode2024\Day20;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day20Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_20.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwenty = new Day20($this->file);

        $reflection = new \ReflectionClass($dayTwenty);
        $mapProperty = $reflection->getProperty('map');
        $mapProperty->setAccessible(true);
        $map = $mapProperty->getValue($dayTwenty);

        $expected = [
            '###############',
            '#...#...#.....#',
            '#.#.#.#.#.###.#',
            '#S#...#.#.#...#',
            '#######.#.#.###',
            '#######.#.#...#',
            '#######.#.###.#',
            '###..E#...#...#',
            '###.#######.###',
            '#...###...#...#',
            '#.#####.#.###.#',
            '#.#...#.#.#...#',
            '#.#.#.#.#.#.###',
            '#...#...#...###',
            '###############',
        ];

        $this->assertEquals($expected, $map);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day20('nonexistent.txt');
    }

    public function test_total_cheats_is_calculated_correctly(): void
    {
        $dayTwenty = new Day20($this->file);

        $this->assertEquals(14 + 14 + 2 + 4 + 2 + 3 + 1 + 1 + 1 + 1 + 1, $dayTwenty->findCheats(2));
        $this->assertEquals(14 + 2 + 4 + 2 + 3 + 1 + 1 + 1 + 1 + 1, $dayTwenty->findCheats(4));
        $this->assertEquals(2 + 4 + 2 + 3 + 1 + 1 + 1 + 1 + 1, $dayTwenty->findCheats(6));
        $this->assertEquals(4 + 2 + 3 + 1 + 1 + 1 + 1 + 1, $dayTwenty->findCheats(8));
        $this->assertEquals(2 + 3 + 1 + 1 + 1 + 1 + 1, $dayTwenty->findCheats(10));
        $this->assertEquals(3 + 1 + 1 + 1 + 1 + 1, $dayTwenty->findCheats(12));
        $this->assertEquals(1 + 1 + 1 + 1 + 1, $dayTwenty->findCheats(20));
        $this->assertEquals(1 + 1 + 1 + 1, $dayTwenty->findCheats(36));
        $this->assertEquals(1 + 1 + 1, $dayTwenty->findCheats(38));
        $this->assertEquals(1 + 1, $dayTwenty->findCheats(40));
        $this->assertEquals(1, $dayTwenty->findCheats(64));
        $this->assertEquals(0, $dayTwenty->findCheats(100));
    }
}

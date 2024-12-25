<?php

namespace Tests\Unit;

use AdventOfCode2024\Day12;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day12Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_12.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwelve = new Day12($this->file);

        $reflection = new \ReflectionClass($dayTwelve);
        $mapProperty = $reflection->getProperty('map');
        $mapProperty->setAccessible(true);
        $map = $mapProperty->getValue($dayTwelve);

        $expected = [
            'RRRRIICCFF',
            'RRRRIICCCF',
            'VVRRRCCFFF',
            'VVRCCCJFFF',
            'VVVVCJJCFE',
            'VVIVCCJJEE',
            'VVIIICJJEE',
            'MIIIIIJJEE',
            'MIIISIJEEE',
            'MMMISSJEEE',
        ];

        $this->assertEquals($expected, $map);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day12('nonexistent.txt');
    }

    public function test_calculate_total_price_returns_correct_result(): void
    {
        $dayTwelve = new Day12($this->file);

        $result = $dayTwelve->calculateTotalPrice();

        $this->assertEquals(1930, $result['part1']);
        $this->assertEquals(1206, $result['part2']);
    }
}

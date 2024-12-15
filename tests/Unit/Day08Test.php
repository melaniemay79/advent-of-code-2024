<?php

namespace Tests\Unit;

use AdventOfCode2024\Day08;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day08Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_08.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayEight = new Day08($this->file);

        $reflection = new \ReflectionClass($dayEight);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayEight);

        $expected = [
            ['.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.', '.', '.', '0', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '0', '.', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.', '.', '0', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '0', '.', '.', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.', 'A', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.', '.', '.', 'A', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.', '.', '.', '.', 'A', '.', '.'],
            ['.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.'],
            ['.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.', '.'],
        ];

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day08('nonexistent.txt');
    }

    public function test_find_antinode_locations(): void
    {
        $dayEight = new Day08($this->file);

        $results = $dayEight->countUniqueAntinodes(false);

        $this->assertEquals(14, $results);
    }

    public function test_find_antinode_locations_in_line(): void
    {
        $dayEight = new Day08($this->file);

        $results = $dayEight->countUniqueAntinodes(true);

        $this->assertEquals(34, $results);
    }
}

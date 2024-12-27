<?php

namespace Tests\Unit;

use AdventOfCode2024\Day25;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day25Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_25.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayTwentyFive = new Day25($this->file);

        $reflection = new \ReflectionClass($dayTwentyFive);
        $keysProperty = $reflection->getProperty('keys');
        $keysProperty->setAccessible(true);
        $keys = $keysProperty->getValue($dayTwentyFive);

        $locksProperty = $reflection->getProperty('locks');
        $locksProperty->setAccessible(true);
        $locks = $locksProperty->getValue($dayTwentyFive);

        $expectedKeys = [
            [5, 0, 2, 1, 3],
            [4, 3, 4, 0, 2],
            [3, 0, 2, 0, 1],
        ];

        $expectedLocks = [
            [0, 5, 3, 4, 3],
            [1, 2, 0, 5, 3],
        ];

        $this->assertEquals($expectedKeys, $keys);
        $this->assertEquals($expectedLocks, $locks);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day25('nonexistent.txt');
    }

    public function test_part_one_returns_correct_value(): void
    {
        $dayTwentyFive = new Day25($this->file);

        $this->assertEquals(3, $dayTwentyFive->part1());
    }
}

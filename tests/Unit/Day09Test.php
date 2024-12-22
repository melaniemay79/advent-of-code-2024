<?php

namespace Tests\Unit;

use AdventOfCode2024\Day09;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day09Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_09.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayNine = new Day09($this->file);

        $reflection = new \ReflectionClass($dayNine);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayNine);

        $expected = '2333133121414131402';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day09('nonexistent.txt');
    }

    public function test_process_input_formats_disk_map_correctly(): void
    {
        $dayNine = new Day09($this->file);

        $sum = $dayNine->checkSum();

        $this->assertEquals(1928, $sum);
    }

    public function test_calculate_checksum_returns_correct_result(): void
    {
        $dayTwelve = new Day09($this->file);

        $result = $dayTwelve->calculateChecksum();

        $this->assertEquals(2858, $result);
    }
}

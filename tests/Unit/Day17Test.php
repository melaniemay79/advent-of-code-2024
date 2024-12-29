<?php

namespace Tests\Unit;

use AdventOfCode2024\Day17;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day17Test extends TestCase
{
    private string $file;

    private string $file2;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_17.txt';
        $this->file2 = __DIR__.'/../data/input_17_pt_2.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $daySeventeen = new Day17($this->file);

        $reflection = new \ReflectionClass($daySeventeen);
        $registerAProperty = $reflection->getProperty('registerA');
        $registerAProperty->setAccessible(true);
        $registerA = $registerAProperty->getValue($daySeventeen);

        $registerBProperty = $reflection->getProperty('registerB');
        $registerBProperty->setAccessible(true);
        $registerB = $registerBProperty->getValue($daySeventeen);

        $registerCProperty = $reflection->getProperty('registerC');
        $registerCProperty->setAccessible(true);
        $registerC = $registerCProperty->getValue($daySeventeen);

        $programProperty = $reflection->getProperty('program');
        $programProperty->setAccessible(true);
        $program = $programProperty->getValue($daySeventeen);

        $this->assertEquals(729, $registerA);
        $this->assertEquals(0, $registerB);
        $this->assertEquals(0, $registerC);
        $this->assertEquals([0, 1, 5, 4, 3, 0], $program);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day17('nonexistent.txt');
    }

    public function test_execute_method_returns_correct_output(): void
    {
        $daySeventeen = new Day17($this->file);
        $this->assertEquals('4,6,3,5,6,3,5,2,1,0', $daySeventeen->execute()[0]);
    }

    public function test_find_self_replicating_value_method_returns_correct_output(): void
    {
        $daySeventeen = new Day17($this->file2);
        $this->assertEquals(117440, $daySeventeen->execute()[1]);
    }
}

<?php

namespace Tests\Unit;

use AdventOfCode2024\Day13;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day13Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_13.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayThirteen = new Day13($this->file);

        $reflection = new \ReflectionClass($dayThirteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayThirteen);

        $expected = 'Button A: X+94, Y+34
Button B: X+22, Y+67
Prize: X=8400, Y=5400

Button A: X+26, Y+66
Button B: X+67, Y+21
Prize: X=12748, Y=12176

Button A: X+17, Y+86
Button B: X+84, Y+37
Prize: X=7870, Y=6450

Button A: X+69, Y+23
Button B: X+27, Y+71
Prize: X=18641, Y=10279';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day13('nonexistent.txt');
    }

    public function test_solve_part_one_returns_correct_result(): void
    {
        $dayThirteen = new Day13($this->file);

        $result = $dayThirteen->solvePart1();

        $this->assertEquals(480, $result);
    }
}

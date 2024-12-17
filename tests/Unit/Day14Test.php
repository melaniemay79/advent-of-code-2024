<?php

namespace Tests\Unit;

use AdventOfCode2024\Day14;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class Day14Test extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = __DIR__.'/../data/input_14.txt';
    }

    public function test_constructor_processes_input_correctly(): void
    {
        $dayFourteen = new Day14($this->file);

        $reflection = new \ReflectionClass($dayFourteen);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $input = $inputProperty->getValue($dayFourteen);

        $expected = 'p=0,4 v=3,-3
p=6,3 v=-1,-3
p=10,3 v=-1,2
p=2,0 v=2,-1
p=0,0 v=1,3
p=3,0 v=-2,-2
p=7,6 v=-1,-3
p=3,0 v=-1,-2
p=9,3 v=2,3
p=7,3 v=-1,2
p=2,4 v=2,-3
p=9,5 v=-3,-3';

        $this->assertEquals($expected, $input);
    }

    public function test_constructor_throws_exception_if_file_is_not_found(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found');

        new Day14('nonexistent.txt');
    }

    public function test_solve_part_one_returns_correct_result(): void
    {
        $dayFourteen = new Day14($this->file);

        $result = $dayFourteen->simulate(100);

        $this->assertEquals(232253028, $result);
    }

    public function test_solve_part_two_returns_correct_result(): void
    {
        $dayFourteen = new Day14($this->file);

        $results = $dayFourteen->findChristmasTree();

        $christmasTree = [
            '*******************************',
            '*.............................*',
            '*.............................*',
            '*.............................*',
            '*.............................*',
            '*..............*..............*',
            '*.............***.............*',
            '*............*****............*',
            '*...........*******...........*',
            '*..........*********..........*',
            '*............*****............*',
            '*...........*******...........*',
            '*..........*********..........*',
            '*.........***********.........*',
            '*........*************........*',
            '*..........*********..........*',
            '*.........***********.........*',
            '*........*************........*',
            '*.......***************.......*',
            '*......*****************......*',
            '*........*************........*',
            '*.......***************.......*',
            '*......*****************......*',
            '*.....*******************.....*',
            '*....*********************....*',
            '*.............***.............*',
            '*.............***.............*',
            '*.............***.............*',
            '*.............................*',
            '*.............................*',
            '*.............................*',
            '*.............................*',
            '*******************************',
        ];

        foreach ($christmasTree as $row) {
            $this->assertTrue(strpos($results[1], $row) !== false);
        }

        $this->assertEquals(8179, $results[0]);
        dump('<pre>'.$results[1].'</pre>');
    }
}

<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day13
{
    private string $input;

    /**
     * @var array<int<0, max>, array<string, array<string, int>>>
     */
    private array $machines;

    /**
     * @param  string  $file
     */
    public function __construct($file)
    {
        if (! file_exists($file)) {
            throw new RuntimeException('File not found');
        }

        $input = file_get_contents($file);

        if ($input === false) {
            exit('Failed to read input file');
        } else {
            $this->input = $input;
        }

        $this->processInput();
    }

    private function processInput(): void
    {
        $machines = [];
        $lines = explode("\n", trim($this->input));

        for ($i = 0; $i < count($lines); $i += 4) {
            if (! isset($lines[$i])) {
                break;
            }

            if (! preg_match('/Button A: X\+(\d+), Y\+(\d+)/', $lines[$i], $matchesA)) {
                continue;
            }
            $buttonA = [
                'x' => (int) $matchesA[1],
                'y' => (int) $matchesA[2],
            ];

            if (! preg_match('/Button B: X\+(\d+), Y\+(\d+)/', $lines[$i + 1], $matchesB)) {
                continue;
            }
            $buttonB = [
                'x' => (int) $matchesB[1],
                'y' => (int) $matchesB[2],
            ];

            if (! preg_match('/Prize: X=(\d+), Y=(\d+)/', $lines[$i + 2], $matchesPrize)) {
                continue;
            }
            $prize = [
                'x' => (int) $matchesPrize[1],
                'y' => (int) $matchesPrize[2],
            ];

            $machines[] = [
                'buttonA' => $buttonA,
                'buttonB' => $buttonB,
                'prize' => $prize,
            ];
        }

        $this->machines = $machines;
    }

    public function solvePart1(): int
    {
        $totalTokens = 0;

        foreach ($this->machines as $machine) {
            $result = $this->solveForMachine($machine);
            if ($result !== null) {
                $totalTokens += $result;
            }
        }

        return $totalTokens;
    }

    /**
     * @param  array<string, array<string, int>>  $machine
     */
    private function solveForMachine(array $machine): ?int
    {
        for ($a = 0; $a <= 100; $a++) {
            for ($b = 0; $b <= 100; $b++) {
                $x = $a * $machine['buttonA']['x'] + $b * $machine['buttonB']['x'];
                $y = $a * $machine['buttonA']['y'] + $b * $machine['buttonB']['y'];

                if ($x === $machine['prize']['x'] && $y === $machine['prize']['y']) {
                    return (3 * $a) + $b;
                }
            }
        }

        return null;
    }

    public function solvePart2(): string
    {
        $correction = '10000000000000';
        $totalTokens = '0';

        foreach ($this->machines as $machine) {
            $result = $this->solveForMachinePart2($machine, $correction);
            if ($result !== null) {
                $totalTokens = bcadd($totalTokens, $result); // @phpstan-ignore-line
            }
        }

        return $totalTokens;
    }

    /**
     * @param  array<string, array<string, int>>  $machine
     */
    private function solveForMachinePart2(array $machine, string $correction): ?string
    {
        $a11 = (string) $machine['buttonA']['x'];
        $a12 = (string) $machine['buttonB']['x'];
        $a21 = (string) $machine['buttonA']['y'];
        $a22 = (string) $machine['buttonB']['y'];

        $b1 = bcadd((string) $machine['prize']['x'], $correction); // @phpstan-ignore-line
        $b2 = bcadd((string) $machine['prize']['y'], $correction); // @phpstan-ignore-line

        $detA = bcsub(bcmul($a11, $a22), bcmul($a12, $a21));

        if ($detA === '0') {
            return null;
        }

        $detX = bcsub(bcmul($b1, $a22), bcmul($a12, $b2));
        $detY = bcsub(bcmul($a11, $b2), bcmul($b1, $a21));

        $a = bcdiv($detX, $detA, 0);
        $b = bcdiv($detY, $detA, 0);

        $x = bcadd(bcmul($a, $a11), bcmul($b, $a12));
        $y = bcadd(bcmul($a, $a21), bcmul($b, $a22));

        $expectedX = bcadd((string) $machine['prize']['x'], $correction); // @phpstan-ignore-line
        $expectedY = bcadd((string) $machine['prize']['y'], $correction); // @phpstan-ignore-line

        if (bccomp($x, $expectedX) === 0 && bccomp($y, $expectedY) === 0) {
            return bcadd(bcmul('3', $a), $b);
        }

        return null;
    }
}

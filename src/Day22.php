<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day22
{
    private string $input;

    /**
     * @var array<int, int>
     */
    private array $nums = [];

    private const PRUNE_MOD = 16777216;

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
        $this->nums = array_map('intval', explode("\n", trim($this->input)));
    }

    public function part1(): int
    {
        $p1 = 0;
        $costs = [];

        foreach ($this->nums as $secret) {
            $costs[] = [];
            for ($i = 0; $i < 2000; $i++) {
                $secret = ($secret << 6 ^ $secret) % self::PRUNE_MOD;
                $secret = ($secret >> 5 ^ $secret) % self::PRUNE_MOD;
                $secret = ($secret << 11 ^ $secret) % self::PRUNE_MOD;
                $costs[count($costs) - 1][] = $secret % 10;
            }
            $p1 += $secret;
        }

        return $p1;
    }

    public function part2(): int
    {
        $costs = [];
        foreach ($this->nums as $secret) {
            $costs[] = [];
            for ($i = 0; $i < 2000; $i++) {
                $secret = ($secret << 6 ^ $secret) % self::PRUNE_MOD;
                $secret = ($secret >> 5 ^ $secret) % self::PRUNE_MOD;
                $secret = ($secret << 11 ^ $secret) % self::PRUNE_MOD;
                $costs[count($costs) - 1][] = $secret % 10;
            }
        }

        $costDeltas = [];
        foreach ($costs as $cost) {
            $deltas = [];
            for ($i = 0; $i < count($cost) - 1; $i++) {
                $deltas[] = $cost[$i + 1] - $cost[$i];
            }
            $costDeltas[] = $deltas;
        }

        $totalCounts = [];
        foreach ($costDeltas as $i => $deltas) {
            $thisCount = [];
            for ($j = 0; $j < count($deltas) - 4; $j++) {
                $pattern = implode(',', array_slice($deltas, $j, 4));
                if (! isset($thisCount[$pattern])) {
                    $thisCount[$pattern] = $costs[$i][$j + 4];
                }
            }
            foreach ($thisCount as $pattern => $count) {
                if (! isset($totalCounts[$pattern])) {
                    $totalCounts[$pattern] = 0;
                }
                $totalCounts[$pattern] += $count;
            }
        }

        return max($totalCounts);
    }
}

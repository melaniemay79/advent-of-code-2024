<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day11
{
    private string $input;

    /**
     * @var array<int>
     */
    private array $stones;

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
        $this->stones = array_map('intval', preg_split('/\s+/', trim($this->input)) ?: []);
    }

    public function simulateBlinks(int $blinks): int
    {
        $stones = $this->stones;
        $stoneCounts = array_count_values($stones);

        for ($blink = 0; $blink < $blinks; $blink++) {
            $newStoneCounts = [];

            foreach ($stoneCounts as $stone => $count) {
                if ($stone === 0) {
                    $newStoneCounts[1] = ($newStoneCounts[1] ?? 0) + $count;
                } elseif ($this->hasEvenDigits($stone)) {
                    $digits = strlen((string) $stone);
                    $half = (int) ($digits / 2);
                    $left = (int) substr((string) $stone, 0, $half);
                    $right = (int) substr((string) $stone, $half);
                    $newStoneCounts[$left] = ($newStoneCounts[$left] ?? 0) + $count;
                    $newStoneCounts[$right] = ($newStoneCounts[$right] ?? 0) + $count;
                } else {
                    if (function_exists('gmp_mul')) {
                        $result = gmp_strval(gmp_mul($stone, 2024));
                        $newStoneCounts[(int) $result] = ($newStoneCounts[(int) $result] ?? 0) + $count;
                    } else {
                        $newStoneCounts[$stone * 2024] = ($newStoneCounts[$stone * 2024] ?? 0) + $count;
                    }
                }
            }

            $stoneCounts = $newStoneCounts;
        }

        return array_sum($stoneCounts);
    }

    private function hasEvenDigits(int $number): bool
    {
        return strlen((string) $number) % 2 === 0;
    }
}

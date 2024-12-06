<?php

namespace AdventOfCode2024\Two;

class DayTwo
{
    /**
     * @var array<int, array<int, int>>
     */
    private array $report = [];

    public function __construct()
    {
        $input = file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($input === false) {
            exit('Failed to read input file');
        }

        $this->processInput($input);
    }

    public function calculateSafe(bool $withSequence = false): int
    {
        $safe = 0;
        foreach ($this->report as $v) {
            if ($withSequence) {
                if (! $this->isUnsafeWithSequence($v)) {
                    $safe++;
                }
            } else {
                if (! $this->isUnsafe($v)) {
                    $safe++;
                }
            }
        }

        return $safe;
    }

    /**
     * @param  array<int, string>  $input
     */
    private function processInput($input): void
    {
        foreach ($input as $line) {
            if (empty(trim($line))) {
                continue;
            }
            $v = explode(' ', trim($line));
            $v = array_map('intval', $v);
            $this->report[] = $v;
        }
    }

    /**
     * @param  int[]  $numbers
     */
    private function isUnsafe(array $numbers): bool
    {
        $isIncreasing = $numbers[1] > $numbers[0];

        for ($i = 0; $i < count($numbers) - 1; $i++) {
            $diff = $numbers[$i + 1] - $numbers[$i];

            if ($isIncreasing && $diff <= 0) {
                return true;
            }
            if (! $isIncreasing && $diff >= 0) {
                return true;
            }

            if (abs($diff) > 3 || $diff == 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  int[]  $numbers
     */
    public function isUnsafeWithSequence(array $numbers): bool
    {
        if (! $this->isUnsafeSequence($numbers)) {
            return false;
        }

        for ($i = 0; $i < count($numbers); $i++) {
            $tempArray = array_values(array_filter($numbers, function ($key) use ($i) {
                return $key !== $i;
            }, ARRAY_FILTER_USE_KEY));

            if (! $this->isUnsafeSequence($tempArray)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  int[]  $numbers
     */
    public function isUnsafeSequence(array $numbers): bool
    {
        if (count($numbers) < 2) {
            return false;
        }

        $isIncreasing = $numbers[1] > $numbers[0];

        for ($i = 0; $i < count($numbers) - 1; $i++) {
            $diff = $numbers[$i + 1] - $numbers[$i];

            if ($isIncreasing && $diff <= 0) {
                return true;
            }
            if (! $isIncreasing && $diff >= 0) {
                return true;
            }

            if (abs($diff) > 3 || $diff == 0) {
                return true;
            }
        }

        return false;
    }
}

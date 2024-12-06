<?php

namespace AdventOfCode2024\One;

class DayOne
{
    /**
     * @var array<int, string>
     */
    private array $set1 = [];

    /**
     * @var array<int, string>
     */
    private array $set2 = [];

    public function __construct()
    {
        $input = file(__DIR__.'/input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($input === false) {
            exit('Failed to read input file');
        }

        $this->processInput($input);
    }

    /**
     * @param  array<int, string>  $input
     */
    private function processInput($input): void
    {
        foreach ($input as $line) {
            $parts = preg_split('/\s+/', trim($line));
            if ($parts !== false && count($parts) >= 2) {
                [$v1, $v2] = $parts;
                $this->set1[] = trim($v1);
                $this->set2[] = trim($v2);
            }
        }
        $this->set1 = array_map('trim', $this->set1);
        $this->set2 = array_map('trim', $this->set2);
    }

    public function calculateSumPartOne(): int
    {
        return array_sum(array_map(function ($v1, $v2) {
            return abs(intval($v1) - intval($v2));
        }, $this->set1, $this->set2));
    }

    public function calculateSumPartTwo(): int
    {
        $sum = 0;
        foreach ($this->set1 as $value) {
            $occurrences = array_count_values($this->set2)[$value] ?? 0;
            $sum += intval($value) * $occurrences;
        }

        return $sum;
    }
}

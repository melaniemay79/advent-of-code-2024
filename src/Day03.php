<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day03
{
    private string $input;

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
    }

    public function calculateSumPartOne(): int
    {
        $sum = 0;
        preg_match_all('/mul\(\d{1,3},\d+\)/', $this->input, $matches);

        foreach ($matches[0] as $match) {
            preg_match('/mul\((\d+),(\d+)\)/', $match, $numbers);
            if (isset($numbers[1]) && isset($numbers[2])) {
                $sum += intval($numbers[1]) * intval($numbers[2]);
            }
        }

        return $sum;
    }

    public function calculateSumPartTwo(): int
    {
        $sum = 0;
        $shouldMultiply = true;

        preg_match_all('/(?:mul\(\d+,\d+\)|do\(\)|don\'t\(\))/', $this->input, $matches);

        foreach ($matches[0] as $operation) {
            if ($operation === 'do()') {
                $shouldMultiply = true;

                continue;
            }

            if ($operation === "don't()") {
                $shouldMultiply = false;

                continue;
            }

            if (preg_match('/mul\((\d+),(\d+)\)/', $operation, $numbers)) {
                if ($shouldMultiply) {
                    $sum += intval($numbers[1]) * intval($numbers[2]);
                }
            }
        }

        return $sum;
    }
}

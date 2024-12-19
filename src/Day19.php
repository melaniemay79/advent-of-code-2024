<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day19
{
    /**
     * @var array<int, string>
     */
    private array $patterns;

    /**
     * @var array<int, string>
     */
    private array $designs;

    /**
     * @var array<string, array<int, int>>
     */
    private array $memo = [];

    /**
     * @param  string  $file
     */
    public function __construct($file)
    {
        if (! file_exists($file)) {
            throw new RuntimeException('File not found');
        }

        $input = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($input === false) {
            exit('Failed to read input file');
        }

        $this->processInput($input);
    }

    /**
     * @param  array<int, string>  $input
     */
    private function processInput(array $input): void
    {
        $this->patterns = array_map('trim', explode(',', $input[0]));
        array_shift($input);
        $this->designs = $input;
    }

    public function solvePart1(): int
    {
        $possibleDesigns = 0;

        foreach ($this->designs as $design) {
            if ($this->canCreateDesign($design)) {
                $possibleDesigns++;
            }
        }

        return $possibleDesigns;
    }

    public function solvePart2(): int
    {
        $this->memo = [];
        $totalCombinations = 0;

        foreach ($this->designs as $design) {
            $combinations = $this->countCombinations($design, 0);
            $totalCombinations += $combinations;
        }

        return $totalCombinations;
    }

    private function canCreateDesign(string $design): bool
    {
        return $this->findCombination($design, 0);
    }

    private function findCombination(string $remaining, int $startPos): bool
    {
        if ($startPos >= strlen($remaining)) {
            return true;
        }

        foreach ($this->patterns as $pattern) {
            if (strlen($remaining) - $startPos >= strlen($pattern) &&
                substr($remaining, $startPos, strlen($pattern)) === $pattern) {
                if ($this->findCombination($remaining, $startPos + strlen($pattern))) {
                    return true;
                }
            }
        }

        return false;
    }

    private function countCombinations(string $remaining, int $startPos): int
    {
        $key = $startPos;
        if (isset($this->memo[$remaining][$key])) {
            return $this->memo[$remaining][$key];
        }

        if ($startPos >= strlen($remaining)) {
            return 1;
        }

        $combinations = 0;

        foreach ($this->patterns as $pattern) {
            if (strlen($remaining) - $startPos >= strlen($pattern) &&
                substr($remaining, $startPos, strlen($pattern)) === $pattern) {
                $combinations += $this->countCombinations($remaining, $startPos + strlen($pattern));
            }
        }

        $this->memo[$remaining][$key] = $combinations;

        return $combinations;
    }
}

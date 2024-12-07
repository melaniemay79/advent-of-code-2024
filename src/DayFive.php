<?php

namespace AdventOfCode2024;

use RuntimeException;

class DayFive
{
    private string $input;

    /**
     * @var array<array<string>>
     */
    private array $rules;

    /**
     * @var array<array<string>>
     */
    private array $updates;

    /**
     * @var array<array<string>>
     */
    private array $sorted;

    /**
     * @var array<array<string>>
     */
    private array $corrected;

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
            $this->input = trim($input);
        }

        $this->processInput();
    }

    private function processInput(): void
    {
        $rules = $updates = [];
        $lines = explode("\n", trim($this->input));
        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            if (strpos($line, '|') !== false) {
                $parts = array_map('trim', explode('|', trim($line)));
                $rules[] = $parts;
            } elseif (strpos($line, ',') !== false) {
                $parts = array_map('trim', explode(',', trim($line)));
                $updates[] = $parts;
            }
        }

        $this->rules = $rules;
        $this->updates = $updates;
    }

    private function handleUpdates(): void
    {
        $sorted = [];
        foreach ($this->updates as $update) {
            if ($this->checkRules($update)) {
                $sorted[] = $update;
            }
        }

        $this->sorted = $sorted;
    }

    private function handleCorrected(): void
    {
        $sorted = [];

        foreach ($this->corrected as $corrected) {
            $rules = [];
            foreach ($this->rules as $rule) {
                if (in_array($rule[0], $corrected) && in_array($rule[1], $corrected)) {
                    $rules[] = $rule;
                }
            }

            $sorted[] = $this->topologicalSortNumbers($corrected, $rules);
        }

        $this->sorted = $sorted;
    }

    /**
     * @param  array<string>  $numbers
     * @param  array<int<0, max>, array<string>>  $rules
     * @return array<string>
     */
    public function topologicalSortNumbers(array $numbers, array $rules): array
    {
        $nodes = array_unique($numbers);

        $inDegree = array_fill_keys($nodes, 0);

        $adjacencyList = [];
        foreach ($nodes as $node) {
            $adjacencyList[$node] = [];
        }

        foreach ($rules as $rule) {
            [$first, $second] = $rule;
            if (in_array($first, $nodes) && in_array($second, $nodes)) {
                $adjacencyList[$first][] = $second;
                $inDegree[$second]++;
            }
        }

        $queue = [];
        foreach ($inDegree as $node => $degree) {
            if ($degree === 0) {
                $queue[] = $node;
            }
        }

        $sorted = [];
        while (! empty($queue)) {
            $node = array_shift($queue);
            $sorted[] = $node;

            foreach ($adjacencyList[$node] as $neighbor) {
                $inDegree[$neighbor]--;
                if ($inDegree[$neighbor] === 0) {
                    $queue[] = $neighbor;
                }
            }
        }

        if (count($sorted) !== count($nodes)) {
            throw new RuntimeException('Graph has at least one cycle.');
        }

        return $sorted;
    }

    public function getSum(): int
    {
        $this->handleUpdates();

        $sum = 0;
        foreach ($this->sorted as $pages) {
            $mid = floor((count($pages) - 1) / 2);
            $sum += $pages[$mid];
        }

        return $sum;
    }

    public function getCorrectedSum(): int
    {
        $this->handleUpdates();
        $this->handleCorrected();
        $sorted = [];
        foreach ($this->corrected as $corrected) {
            $sorted[] = $this->topologicalSortNumbers($corrected, $this->rules);
        }
        $sum = 0;
        foreach ($sorted as $pages) {
            $mid = floor((count($pages) - 1) / 2);
            $sum += $pages[$mid];
        }

        return $sum;
    }

    /**
     * @param  array<string>  $update
     */
    private function checkRules($update): bool
    {
        foreach ($this->rules as $rule) {
            if (in_array($rule[0], $update) && in_array($rule[1], $update)) {
                $key1 = array_search($rule[0], $update);
                $key2 = array_search($rule[1], $update);
                if ($key1 > $key2) {
                    $this->corrected[] = $update;

                    return false;
                }
            }
        }

        return true;
    }
}

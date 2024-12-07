<?php

namespace AdventOfCode2024;

use RuntimeException;

class DayFive
{
    private string $input;

    /**
     * @var array<int, array<int, int>|bool>
     */
    private array $rules;

    /**
     * @var array<int, array<int, int>>
     */
    private array $updates;

    /**
     * @var array<int<0, max>, array<int, int>>
     */
    private array $set1;

    /**
     * @var array<int<0, max>, array<int, int>>
     */
    private array $set2;

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
        $lines = explode("\n\n", trim($this->input));

        $rules = array_map(function ($line) {
            return array_map('intval', explode('|', trim($line)));
        }, explode("\n", trim($lines[0])));

        $updates = array_map(function ($line) {
            return array_map('intval', explode(',', trim($line)));
        }, explode("\n", trim($lines[1])));

        $this->rules = $rules;
        $this->updates = $updates;
    }

    /**
     * @param  array<int, array<int, int>|bool>  $rules
     */
    private function handleRules($rules): void
    {
        $this->rules = [];
        foreach ($rules as $rule) {
            if (is_array($rule) && isset($rule[0], $rule[1])) {
                $this->rules[($rule[0] << 8) + $rule[1]] = true;
            }
        }
    }

    private function handleUpdates(): void
    {
        $sorted = [];
        $corrected = [];
        foreach ($this->updates as $update) {
            if ($this->checkRules($update)) {
                $sorted[] = $update;
            } else {
                usort($update, function ($a, $b) {
                    if (isset($this->rules[($a << 8) + $b])) {
                        return -1;
                    }
                    if (isset($this->rules[($b << 8) + $a])) {
                        return 1;
                    }

                    return 0;
                });
                $corrected[] = $update;
            }
        }

        $this->set1 = $sorted;
        $this->set2 = $corrected;
    }

    /**
     * @param  bool  $corrected
     */
    public function getSum($corrected = false): int
    {
        $this->handleRules($this->rules);
        $this->handleUpdates();

        if ($corrected) {
            $set = $this->set2;
        } else {
            $set = $this->set1;
        }
        $sum = 0;
        foreach ($set as $pages) {
            $mid = floor((count($pages) - 1) / 2);
            $sum += $pages[$mid];
        }

        return $sum;
    }

    /**
     * @param  array<int, int>  $update
     */
    private function checkRules($update): bool
    {
        for ($i = 1; $i < count($update); $i++) {
            $a = $update[$i - 1];
            $b = $update[$i];
            if (isset($this->rules[($b << 8) + $a])) {
                return false;
            }
        }

        return true;
    }
}

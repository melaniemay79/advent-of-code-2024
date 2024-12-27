<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day24
{
    private string $input;

    /**
     * @var array<string, int>
     */
    private array $wires = [];

    /**
     * @var array<string, array<string>>
     */
    private array $rules = [];

    /**
     * @var array<string, callable>
     */
    private array $ops;

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

        $this->ops = [
            'AND' => fn ($a, $b) => $a & $b,
            'OR' => fn ($a, $b) => $a | $b,
            'XOR' => fn ($a, $b) => $a ^ $b,
        ];

        $this->processInput();
    }

    private function processInput(): void
    {
        $lines = explode("\n", trim($this->input));
        $midIndex = array_search('', $lines);

        for ($i = 0; $i < $midIndex; $i++) {
            [$key, $value] = explode(': ', $lines[$i]);
            $this->wires[$key] = (int) $value;
        }

        $ruleLines = array_slice($lines, $midIndex + 1);
        foreach ($ruleLines as $line) {
            $parts = explode(' ', $line);
            if (count($parts) >= 5) {
                $this->rules[$parts[4]] = array_slice($parts, 0, 3);
            }
        }
    }

    public function part1(): int
    {
        $ruleQueue = array_keys($this->rules);

        while (! empty($ruleQueue)) {
            $wire = array_pop($ruleQueue);
            if (isset($this->wires[$wire])) {
                continue;
            }

            [$left, $op, $right] = $this->rules[$wire];
            $leftOp = $this->wires[$left] ?? null;
            $rightOp = $this->wires[$right] ?? null;

            if ($leftOp === null || $rightOp === null) {
                $ruleQueue[] = $wire;
                if ($leftOp === null) {
                    $ruleQueue[] = $left;
                }
                if ($rightOp === null) {
                    $ruleQueue[] = $right;
                }
            } else {
                $this->wires[$wire] = ($this->ops[$op])($leftOp, $rightOp);
            }
        }

        $zWires = array_filter(array_keys($this->wires), fn ($wire) => str_starts_with($wire, 'z'));
        rsort($zWires);

        $zVals = implode('', array_map(fn ($wire) => $this->wires[$wire], $zWires));

        return (int) bindec($zVals);
    }

    public function part2(): string
    {
        $wrong = [];
        $highestZ = 'z00';

        foreach ($this->rules as $wire => $rule) {
            if (str_starts_with($wire, 'z') && $wire > $highestZ) {
                $highestZ = $wire;
            }
        }

        foreach ($this->rules as $wire => $rule) {
            [$op1, $op, $op2] = $rule;

            if (str_starts_with($wire, 'z') && $op !== 'XOR' && $wire !== $highestZ) {
                $wrong[] = $wire;
            }

            if ($op === 'XOR'
                && ! str_starts_with($wire, 'x') && ! str_starts_with($wire, 'y') && ! str_starts_with($wire, 'z')
                && ! str_starts_with($op1, 'x') && ! str_starts_with($op1, 'y') && ! str_starts_with($op1, 'z')
                && ! str_starts_with($op2, 'x') && ! str_starts_with($op2, 'y') && ! str_starts_with($op2, 'z')
            ) {
                $wrong[] = $wire;
            }

            if ($op === 'AND' && $op1 !== 'x00' && $op2 !== 'x00') {
                foreach ($this->rules as $subRule) {
                    if (($wire === $subRule[0] || $wire === $subRule[2]) && $subRule[1] !== 'OR') {
                        $wrong[] = $wire;
                        break;
                    }
                }
            }

            if ($op === 'XOR') {
                foreach ($this->rules as $subRule) {
                    if (($wire === $subRule[0] || $wire === $subRule[2]) && $subRule[1] === 'OR') {
                        $wrong[] = $wire;
                        break;
                    }
                }
            }
        }

        $wrong = array_unique($wrong);
        sort($wrong);

        return implode(',', $wrong);
    }
}

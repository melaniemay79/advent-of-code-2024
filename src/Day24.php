<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day24
{
    private string $input;

    private array $wires = [];

    private array $rules = [];

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
            $this->rules[$parts[4]] = array_slice($parts, 0, 3);
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

        return bindec($zVals);
    }
}

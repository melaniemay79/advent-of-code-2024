<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day16
{
    private string $input;

    /**
     * @var array<int, array<int, string>>
     */
    private array $grid = [];

    /**
     * @var array<int, int>
     */
    private array $start;

    /**
     * @var array<int, int>
     */
    private array $end;

    private const DIRECTIONS = [
        'N' => [-1, 0],
        'E' => [0, 1],
        'S' => [1, 0],
        'W' => [0, -1],
    ];

    public function __construct(string $file)
    {
        if (! file_exists($file)) {
            throw new RuntimeException('File not found');
        }

        $input = file_get_contents($file);

        if ($input === false) {
            exit('Failed to read input file');
        }

        $this->input = $input;
        $this->parseInput();
    }

    private function parseInput(): void
    {
        $lines = explode("\n", trim($this->input));
        foreach ($lines as $i => $line) {
            $this->grid[$i] = str_split($line);
            foreach ($this->grid[$i] as $j => $char) {
                if ($char === 'S') {
                    $this->start = [$i, $j];
                } elseif ($char === 'E') {
                    $this->end = [$i, $j];
                }
            }
        }
    }

    /**
     * @return array{part1: int, part2: int}
     */
    public function solve(): array
    {
        $all = [];
        $z = [];

        $startDir = [$this->start, self::DIRECTIONS['E']];
        $all[json_encode($startDir)] = 0;
        $z[json_encode($startDir)] = true;

        while (! empty($z)) {
            $key = array_key_first($z);
            unset($z[$key]);
            /** @var array{0: array{int, int}, 1: array{int, int}} $state */
            $state = json_decode((string) $key, true);
            $cost = $all[$key];

            foreach ($this->next($state) as $new => $inc) {
                /** @var array{0: array{int, int}, 1: array{int, int}} $newState */
                $newState = json_decode((string) $new, true);
                [$b, $c] = $newState;
                if (! isset($this->grid[$b[0]][$b[1]]) || $this->grid[$b[0]][$b[1]] === '#') {
                    continue;
                }

                $newCost = $cost + $inc;
                if (! isset($all[$new]) || $newCost < $all[$new]) {
                    $all[$new] = $newCost;
                    $z[$new] = true;
                }
            }
        }

        $min = PHP_INT_MAX;
        $last = null;
        foreach (self::DIRECTIONS as $dir) {
            $state = json_encode([$this->end, $dir]);
            if (isset($all[$state]) && $all[$state] < $min) {
                $min = $all[$state];
                $last = $state;
            }
        }

        $xsOnPath = [$this->end[0].','.$this->end[1] => true];
        $pending = [$last => true];

        while (! empty($pending)) {
            $key = array_key_first($pending);
            unset($pending[$key]);
            /** @var array{0: array{int, int}, 1: array{int, int}} $state */
            $state = json_decode((string) $key, true);
            $cost = $all[$key];

            foreach ($this->prev($state) as $prevState => $inc) {
                /** @var array{0: array{int, int}, 1: array{int, int}} $prevStateData */
                $prevStateData = json_decode((string) $prevState, true);
                [$d, $y] = $prevStateData;

                if (! isset($this->grid[$d[0]][$d[1]]) || $this->grid[$d[0]][$d[1]] === '#') {
                    continue;
                }

                if (isset($all[$prevState]) && ($cost === $all[$prevState] + $inc)) {
                    $pending[$prevState] = true;
                    $xsOnPath[$d[0].','.$d[1]] = true;
                }
            }
        }

        return [
            'part1' => $min,
            'part2' => count($xsOnPath),
        ];
    }

    /**
     * @param  array{0: array{int, int}, 1: array{int, int}}  $state
     * @return array<int|string, int>
     */
    private function next(array $state): array
    {
        [$x, $dir] = $state;
        $nextStates = [];

        $b = $this->add($x, $dir);
        $nextStates[json_encode([$b, $dir])] = 1;

        $c = [$dir[1], -$dir[0]];
        $nextStates[json_encode([$x, $c])] = 1000;

        $c = [-$dir[1], $dir[0]];
        $nextStates[json_encode([$x, $c])] = 1000;

        return $nextStates;
    }

    /**
     * @param  array{0: array{int, int}, 1: array{int, int}}  $state
     * @return array<int|string, int>
     */
    private function prev(array $state): array
    {
        [$x, $dir] = $state;
        $prev = [];

        $d = $this->add($x, [-$dir[0], -$dir[1]]);
        $prev[json_encode([$d, $dir])] = 1;

        $y = [-$dir[1], $dir[0]];
        $prev[json_encode([$x, $y])] = 1000;

        $y = [$dir[1], -$dir[0]];
        $prev[json_encode([$x, $y])] = 1000;

        return $prev;
    }

    /**
     * @param  array{int, int}  $p1
     * @param  array{int, int}  $p2
     * @return array{int|string, int}
     */
    private function add(array $p1, array $p2): array
    {
        return [$p1[0] + $p2[0], $p1[1] + $p2[1]];
    }
}

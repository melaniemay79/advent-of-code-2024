<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day16
{
    private string $input;

    /**
     * @var array<int, array<int, string>>
     */
    private array $grid;

    /**
     * @var array<int, int>
     */
    private array $start;

    /**
     * @var array<int, int>
     */
    private array $end;

    private int $minScore = PHP_INT_MAX;

    /**
     * @var array<array<array<bool>|bool>>
     */
    private array $optimalRoutes = [];

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
        $lines = explode("\n", trim($this->input));
        $this->grid = [];

        foreach ($lines as $y => $line) {
            $this->grid[] = str_split($line);

            if (($x = strpos($line, 'S')) !== false) {
                $this->start = [$x, $y];
            }
            if (($x = strpos($line, 'E')) !== false) {
                $this->end = [$x, $y];
            }
        }
    }

    /**
     * @return array<string, int>
     */
    public function solve(): array
    {
        $weights = array_fill(0, count($this->grid), array_fill(0, count($this->grid[0]), PHP_INT_MAX));

        $startDir = [false, true, false, false];
        $visited = [];
        $currentPath = [];

        $this->findShortestPath(
            $startDir,
            $visited,
            $this->start[0],
            $this->start[1],
            0,
            $weights,
            $currentPath
        );

        $uniqueTiles = [];
        foreach ($this->optimalRoutes as $path) {
            foreach ($path as $coord => $_) {
                $uniqueTiles[$coord] = true;
            }
        }
        $uniqueTiles["{$this->start[1]},{$this->start[0]}"] = true;
        $uniqueTiles["{$this->end[1]},{$this->end[0]}"] = true;

        return [
            'part1' => $this->minScore,
            'part2' => count($uniqueTiles),
        ];
    }

    /**
     * @param  array<array-key, bool>  $dir
     * @param  array<array-key, bool>  $visited
     * @param  array<array-key, bool>  $currentPath
     * @param  array<array-key, array<array-key, int>>  $weights
     */
    private function findShortestPath(
        array $dir,
        array $visited,
        int $x,
        int $y,
        int $currentScore,
        array &$weights,
        array &$currentPath
    ): void {
        if ($currentScore > $weights[$y][$x] + 1000) {
            return;
        }
        if ($currentScore > $this->minScore) {
            return;
        }

        if ($x === $this->end[0] && $y === $this->end[1]) {
            if ($currentScore <= $this->minScore) {
                if ($currentScore < $this->minScore) {
                    $this->minScore = $currentScore;
                    $this->optimalRoutes = [];
                }
                $this->optimalRoutes[] = $currentPath;
            }

            return;
        }

        $visited["$y,$x"] = true;
        $weights[$y][$x] = $currentScore;
        $currentPath["$y,$x"] = true;

        $directions = [
            [0, 1, [false, true, false, false]],  // right
            [1, 0, [true, false, false, false]],  // down
            [0, -1, [false, false, true, false]], // left
            [-1, 0, [false, false, false, true]],  // up
        ];

        foreach ($directions as [$dx, $dy, $newDir]) {
            $newX = $x + $dx;
            $newY = $y + $dy;

            if ($this->isSafe($newX, $newY, $visited)) {
                $costIncrease = ($dir === $newDir) ? 1 : 1001;

                $this->findShortestPath(
                    $newDir,
                    $visited,
                    $newX,
                    $newY,
                    $currentScore + $costIncrease,
                    $weights,
                    $currentPath
                );
            }
        }

        unset($visited["$y,$x"]);
        unset($currentPath["$y,$x"]);
    }

    /**
     * @param  array<array-key, bool>  $visited
     */
    private function isSafe(int $x, int $y, array $visited): bool
    {
        return isset($this->grid[$y][$x]) && $this->grid[$y][$x] !== '#' && ! isset($visited["$y,$x"]);
    }
}

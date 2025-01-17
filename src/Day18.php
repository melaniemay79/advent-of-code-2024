<?php

namespace AdventOfCode2024;

use RuntimeException;
use SplPriorityQueue;

class Day18
{
    /**
     * @var array<int, array<int, string>>
     */
    private array $grid;

    /**
     * @var array<int, array<int, int>>
     */
    private array $bytes;

    private int $gridSize;

    private int $maxBytes;

    public function __construct(string $file, int $gridSize, int $maxBytes)
    {
        if (! file_exists($file)) {
            throw new RuntimeException('File not found');
        }

        $input = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($input === false) {
            exit('Failed to read input file');
        }

        $this->bytes = [];
        $this->gridSize = $gridSize;
        $this->maxBytes = $maxBytes;
        $this->processInput($input);
        $this->buildGrid();
    }

    /**
     * @param  array<int, string>  $input
     */
    private function processInput(array $input): void
    {
        $bytes = [];
        foreach ($input as $line) {
            $bytes[] = array_map('intval', explode(',', $line));
        }
        $this->bytes = $bytes;
    }

    private function buildGrid(): void
    {
        for ($y = 0; $y < $this->gridSize; $y++) {
            $this->grid[$y] = array_fill(0, $this->gridSize, '.');
        }
        $i = 0;
        foreach ($this->bytes as $byte) {
            if ($i > $this->maxBytes) {
                break;
            }

            if (empty($byte)) {
                continue;
            }

            $x = intval($byte[0]);
            $y = intval($byte[1]);

            if ($x >= 0 && $x < $this->gridSize &&
                $y >= 0 && $y < $this->gridSize) {
                $this->grid[$x][$y] = '#';
            }
            $i++;
        }
    }

    public function findShortestPath(): int
    {
        $queue = new SplPriorityQueue;
        $visited = array_fill(0, $this->gridSize, array_fill(0, $this->gridSize, false));
        $distances = array_fill(0, $this->gridSize, array_fill(0, $this->gridSize, PHP_INT_MAX));
        $directions = [[0, 1], [1, 0], [0, -1], [-1, 0]];

        $distances[0][0] = 0;
        $queue->insert([0, 0], 0);
        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

        while (! $queue->isEmpty()) {
            /**
             * @var array{data: array{0: int, 1: int}}
             */
            $current = $queue->extract();
            $data = (array) $current['data'];
            [$x, $y] = $data;

            if ($visited[$y][$x]) {
                continue;
            }

            $visited[$y][$x] = true;
            $currentDist = $distances[$y][$x];

            if ($x === $this->gridSize - 1 && $y === $this->gridSize - 1) {
                return (int) $currentDist;
            }

            foreach ($directions as [$dx, $dy]) {
                $newX = $x + $dx;
                $newY = $y + $dy;

                if ($newX >= 0 && $newX < $this->gridSize &&
                    $newY >= 0 && $newY < $this->gridSize &&
                    ! $visited[$newY][$newX] &&
                    $this->grid[$newY][$newX] === '.') {

                    $newDist = $currentDist + 1;
                    if ($newDist < $distances[$newY][$newX]) {
                        $distances[$newY][$newX] = $newDist;
                        $queue->insert([$newX, $newY], -$newDist);
                    }
                }
            }
        }

        return -1;
    }

    public function findBlockingByte(): string
    {
        for ($i = 0; $i < count($this->bytes); $i++) {
            $byte = $this->bytes[$i];
            $x = intval($byte[0]);
            $y = intval($byte[1]);

            if ($x >= 0 && $x < $this->gridSize &&
                $y >= 0 && $y < $this->gridSize) {
                $this->grid[$x][$y] = '#';
            }

            if (! $this->isPathPossible()) {
                return "$x,$y";
            }
        }

        return '';
    }

    private function isPathPossible(): bool
    {
        $queue = new SplPriorityQueue;
        $visited = array_fill(0, $this->gridSize, array_fill(0, $this->gridSize, false));
        $directions = [[0, 1], [1, 0], [0, -1], [-1, 0]];

        $queue->insert([0, 0], 0);

        while (! $queue->isEmpty()) {
            $current = (array) $queue->extract();
            [$x, $y] = $current;

            if ($x === $this->gridSize - 1 && $y === $this->gridSize - 1) {
                return true;
            }

            foreach ($directions as [$dx, $dy]) {
                $newX = $x + $dx; // @phpstan-ignore-line
                $newY = $y + $dy; // @phpstan-ignore-line

                if ($newX >= 0 && $newX < $this->gridSize &&
                    $newY >= 0 && $newY < $this->gridSize &&
                    ! $visited[$newY][$newX] &&
                    $this->grid[$newY][$newX] === '.') {

                    $visited[$newY][$newX] = true;
                    $queue->insert([$newX, $newY], 0);
                }
            }
        }

        return false;
    }
}

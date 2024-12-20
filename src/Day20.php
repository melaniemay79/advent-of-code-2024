<?php

namespace AdventOfCode2024;

use RuntimeException;
use SplPriorityQueue;

class Day20
{
    /**
     * @var array<int, string>
     */
    private array $map;

    /**
     * @var array<int, int>
     */
    private array $start;

    /**
     * @var array<int, int>
     */
    private array $end;

    private int $width;

    private int $height;

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

        $this->map = $input;
        $this->width = strlen($input[0]);
        $this->height = count($input);
        $this->start = $this->findPosition('S');
        $this->end = $this->findPosition('E');
    }

    /**
     * @return array<int, int>
     */
    private function findPosition(string $char): array
    {
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                if ($this->map[$y][$x] === $char) {
                    return [$y, $x];
                }
            }
        }
        throw new RuntimeException("Position $char not found");
    }

    /**
     * @param  array<int, int>  $start
     * @param  array<int, int>  $end
     */
    private function findShortestPath(array $start, array $end, bool $allowWalls = false): ?int
    {
        $queue = new SplPriorityQueue;
        $visited = [];
        $queue->insert([$start[0], $start[1], 0], 0);

        while (! $queue->isEmpty()) {
            /** @var array{0: int, 1: int, 2: int} $current */
            $current = $queue->extract();
            [$y, $x, $steps] = $current;

            if ([$y, $x] === $end) {
                return $steps;
            }

            $key = "$y,$x";
            if (isset($visited[$key])) {
                continue;
            }
            $visited[$key] = true;

            foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$dy, $dx]) {
                $newY = $y + $dy;
                $newX = $x + $dx;

                if ($newY < 0 || $newY >= $this->height || $newX < 0 || $newX >= $this->width) {
                    continue;
                }

                if (! $allowWalls && $this->map[$newY][$newX] === '#') {
                    continue;
                }

                $queue->insert([$newY, $newX, $steps + 1], -($steps + 1));
            }
        }

        return null;
    }

    public function findCheats(int $savedTime): int
    {
        $normalPath = $this->findShortestPath($this->start, $this->end);
        $cheats = 0;

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                if ($this->map[$y][$x] === '#') {
                    continue;
                }

                foreach ([[-2, 0], [2, 0], [0, -2], [0, 2], [-1, -1], [-1, 1], [1, -1], [1, 1]] as [$dy, $dx]) {
                    $endY = $y + $dy;
                    $endX = $x + $dx;

                    if ($endY < 0 || $endY >= $this->height ||
                        $endX < 0 || $endX >= $this->width ||
                        $this->map[$endY][$endX] === '#') {
                        continue;
                    }

                    $hasWall = false;
                    for ($i = 1; $i <= 2; $i++) {
                        $checkY = (int) round($y + ($dy * $i / 2));
                        $checkX = (int) round($x + ($dx * $i / 2));

                        if ($checkY >= 0 && $checkY < $this->height &&
                            $checkX >= 0 && $checkX < $this->width &&
                            $this->map[$checkY][$checkX] === '#') {
                            $hasWall = true;
                            break;
                        }
                    }

                    if (! $hasWall) {
                        continue;
                    }

                    $pathToCheat = $this->findShortestPath($this->start, [$y, $x]);
                    $pathFromCheat = $this->findShortestPath([$endY, $endX], $this->end);

                    if ($pathToCheat !== null && $pathFromCheat !== null) {
                        $totalPath = $pathToCheat + $pathFromCheat + 2;
                        $saved = $normalPath - $totalPath;

                        if ($saved >= $savedTime) {
                            $cheats++;
                        }
                    }
                }
            }
        }

        return $cheats;
    }
}

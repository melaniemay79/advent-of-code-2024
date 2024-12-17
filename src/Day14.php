<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day14
{
    private string $input;

    /** @var array<int, array{position: array{0: int, 1: int}, velocity: array{0: int, 1: int}}> */
    private array $robots;

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
        $maxX = 0;
        $maxY = 0;

        foreach ($lines as $line) {
            if (preg_match('/p=(\d+),(\d+) v=(-?\d+),(-?\d+)/', $line, $matches)) {
                $x = (int) $matches[1];
                $y = (int) $matches[2];

                $maxX = max($maxX, $x);
                $maxY = max($maxY, $y);

                $this->robots[] = [
                    'position' => [$x, $y],
                    'velocity' => [(int) $matches[3], (int) $matches[4]],
                ];
            }
        }

        $this->width = $maxX + 1;
        $this->height = $maxY + 1;
    }

    public function simulate(int $seconds): int
    {
        foreach ($this->robots as &$robot) {
            $newX = $robot['position'][0] + ($seconds * $robot['velocity'][0]);
            $newY = $robot['position'][1] + ($seconds * $robot['velocity'][1]);

            $robot['position'][0] = $this->safeModulo($this->safeModulo($newX, $this->width) + $this->width, $this->width);
            $robot['position'][1] = $this->safeModulo($this->safeModulo($newY, $this->height) + $this->height, $this->height);
        }

        return $this->calculateSafetyFactor();
    }

    private function safeModulo(int $n, int $m): int
    {
        return (($n % $m) + $m) % $m;
    }

    private function calculateSafetyFactor(): int
    {
        $quadrants = [0, 0, 0, 0];
        $midX = floor($this->width / 2);
        $midY = floor($this->height / 2);

        foreach ($this->robots as $robot) {
            [$x, $y] = $robot['position'];

            if ($x < $midX && $y < $midY) {
                $quadrants[0]++;
            } elseif ($x > $midX && $y < $midY) {
                $quadrants[1]++;
            } elseif ($x < $midX && $y > $midY) {
                $quadrants[2]++;
            } elseif ($x > $midX && $y > $midY) {
                $quadrants[3]++;
            } else {
            }
        }

        return (int) array_product($quadrants);
    }

    /**
     * @return array{0: int, 1: string}
     */
    public function findChristmasTree(): array
    {
        $seconds = 0;

        while ($seconds < 10000) {
            $seconds++;

            $grid = array_fill(0, $this->height, array_fill(0, $this->width, 0));

            $hasOverlap = false;

            foreach ($this->robots as $robot) {
                $px = $robot['position'][0];
                $py = $robot['position'][1];
                $vx = $robot['velocity'][0];
                $vy = $robot['velocity'][1];

                $nx = ($px + $seconds * $vx) % $this->width;
                $ny = ($py + $seconds * $vy) % $this->height;

                if ($nx < 0) {
                    $nx += $this->width;
                }
                if ($ny < 0) {
                    $ny += $this->height;
                }

                $grid[$ny][$nx]++;

                if ($grid[$ny][$nx] > 1) {
                    $hasOverlap = true;
                    break;
                }
            }

            if (! $hasOverlap) {
                $christmasTree = '';
                foreach ($grid as $row) {
                    $christmasTree .= implode('', array_map(function ($cell) {
                        return $cell === 1 ? '*' : '.';
                    }, $row))."\n";
                }

                return [$seconds, $christmasTree];
            }
        }

        return [-1, ''];
    }
}

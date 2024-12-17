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
        for ($i = 0; $i < $seconds; $i++) {
            foreach ($this->robots as &$robot) {
                $newX = $robot['position'][0] + $robot['velocity'][0];
                $newY = $robot['position'][1] + $robot['velocity'][1];

                while ($newX < 0) {
                    $newX += $this->width;
                }
                while ($newX >= $this->width) {
                    $newX -= $this->width;
                }
                while ($newY < 0) {
                    $newY += $this->height;
                }
                while ($newY >= $this->height) {
                    $newY -= $this->height;
                }

                $robot['position'][0] = $newX;
                $robot['position'][1] = $newY;
            }
        }

        return $this->calculateSafetyFactor();
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
}

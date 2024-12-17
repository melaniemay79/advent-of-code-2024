<?php

namespace AdventOfCode2024;

use RuntimeException;
use SplPriorityQueue;

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

    public function solve(): int
    {
        return $this->findShortestPath();
    }

    private function findShortestPath(): int
    {
        $queue = new SplPriorityQueue;
        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);

        $queue->insert([$this->start[0], $this->start[1], 0, 0], 0);  // East (no turn)
        $queue->insert([$this->start[0], $this->start[1], 1, 1000], -1000);  // South (one turn)
        $queue->insert([$this->start[0], $this->start[1], 2, 1000], -1000);  // West (one turn)
        $queue->insert([$this->start[0], $this->start[1], 3, 1000], -1000);  // North (one turn)

        $visited = [];
        $directions = [[0, 1], [1, 0], [0, -1], [-1, 0]]; // right, down, left, up
        $minScore = PHP_INT_MAX;

        while (! $queue->isEmpty()) {
            $extracted = $queue->extract();
            if (! is_array($extracted) || ! isset($extracted['data']) || ! is_array($extracted['data'])) {
                continue;
            }

            $current = $extracted['data'];
            if (count($current) !== 4) {
                continue;
            }

            [$x, $y, $dir, $score] = array_map('intval', $current);

            $state = "$x,$y,$dir";
            if (isset($visited[$state]) && $visited[$state] <= $score) {
                continue;
            }
            $visited[$state] = $score;

            if ($x === $this->end[0] && $y === $this->end[1]) {
                $minScore = min($minScore, $score);

                continue;
            }

            $newX = $x + $directions[$dir][0];
            $newY = $y + $directions[$dir][1];
            if ($this->isValid($newX, $newY)) {
                $queue->insert(
                    [$newX, $newY, $dir, $score + 1],
                    -($score + 1)
                );
            }

            foreach ([($dir + 3) % 4, ($dir + 1) % 4] as $newDir) {
                $checkX = $x + $directions[$newDir][0];
                $checkY = $y + $directions[$newDir][1];
                if ($this->isValid($checkX, $checkY)) {
                    $queue->insert(
                        [$x, $y, $newDir, $score + 1000],
                        -($score + 1000)
                    );
                }
            }
        }

        return $minScore;
    }

    private function isValid(int $x, int $y): bool
    {
        return isset($this->grid[$y][$x]) && $this->grid[$y][$x] !== '#';
    }
}

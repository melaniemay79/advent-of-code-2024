<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day10
{
    /**
     * @var array<int, array<int, string>>
     */
    private array $input;

    private int $totalScore = 0;

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
            throw new RuntimeException('Failed to read input file');
        }

        $this->processInput($input);
    }

    /**
     * @param  array<int, string>  $input
     */
    private function processInput($input): void
    {
        foreach ($input as $row) {
            $this->input[] = str_split($row);
        }
    }

    public function calculateTrailheadScores(): int
    {
        $rows = count($this->input);
        $cols = count($this->input[0]);

        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {
                if ($this->input[$row][$col] === '0') {
                    $this->totalScore += $this->countNinesFromTrailhead($row, $col);
                }
            }
        }

        return $this->totalScore;
    }

    private function countNinesFromTrailhead(int $startRow, int $startCol): int
    {
        $visited = [];
        $stack = [[$startRow, $startCol]];
        $countNines = 0;

        while (! empty($stack)) {
            [$row, $col] = array_pop($stack);

            if ($this->input[$row][$col] === '9') {
                $countNines++;
            }

            $visited["$row,$col"] = true;

            foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$dRow, $dCol]) {
                $newRow = $row + $dRow;
                $newCol = $col + $dCol;

                if ($this->isValidPosition($newRow, $newCol, $visited, $this->input[$row][$col])) {
                    $stack[] = [$newRow, $newCol];
                }
            }
        }

        return $countNines;
    }

    /**
     * @param  array<string, bool>  $visited
     */
    private function isValidPosition(int $row, int $col, array $visited, string $currentHeight): bool
    {
        if ($row < 0 || $row >= count($this->input) || $col < 0 || $col >= count($this->input[0])) {
            return false;
        }
        if (isset($visited["$row,$col"])) {
            return false;
        }

        return (int) $this->input[$row][$col] === (int) $currentHeight + 1;
    }

    private function countDistinctTrails(int $startRow, int $startCol): int
    {
        return $this->findPaths($startRow, $startCol, []);
    }

    /**
     * @param  array<int, array<int, bool>|string>  $path
     */
    private function findPaths(int $row, int $col, array $path): int
    {
        // Add current position to path
        $path[] = "$row,$col";

        // If we reached a 9, we found a valid path
        if ($this->input[$row][$col] === '9') {
            return 1;
        }

        $paths = 0;
        $currentHeight = (int) $this->input[$row][$col];

        // Try all four directions
        foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$dRow, $dCol]) {
            $newRow = $row + $dRow;
            $newCol = $col + $dCol;

            // Check if the new position is valid and hasn't been visited in current path
            if ($this->isValidNextPosition($newRow, $newCol, $currentHeight) &&
                ! in_array("$newRow,$newCol", $path)) {
                $paths += $this->findPaths($newRow, $newCol, $path);
            }
        }

        return $paths;
    }

    private function isValidNextPosition(int $row, int $col, int $currentHeight): bool
    {
        if ($row < 0 || $row >= count($this->input) || $col < 0 || $col >= count($this->input[0])) {
            return false;
        }

        return (int) $this->input[$row][$col] === $currentHeight + 1;
    }

    public function calculateTrailheadRatings(): int
    {
        $rows = count($this->input);
        $cols = count($this->input[0]);
        $totalRatings = 0; // Initialize total ratings

        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {
                if ($this->input[$row][$col] === '0') {
                    $totalRatings += $this->countDistinctTrails($row, $col); // Count distinct trails
                }
            }
        }

        return $totalRatings; // Return total ratings instead of total score
    }

    public function getTotalTrailheadRatings(): int
    {
        return $this->calculateTrailheadRatings(); // Call the updated method
    }
}

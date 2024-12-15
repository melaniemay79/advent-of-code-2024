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
}

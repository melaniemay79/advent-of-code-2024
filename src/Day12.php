<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day12
{
    /**
     * @var array<int, string>
     */
    private array $map;

    private int $rows;

    private int $cols;

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
        }

        $this->processInput($input);
    }

    private function processInput(string $input): void
    {
        $this->map = explode("\n", trim($input));
        $this->rows = count($this->map);
        $this->cols = strlen($this->map[0]);
    }

    public function calculateTotalPrice(): int
    {
        $visited = array_fill(0, $this->rows, array_fill(0, $this->cols, false));
        $totalPrice = 0;

        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                if (! $visited[$i][$j]) {
                    $regionData = $this->exploreRegion($i, $j, $visited);
                    $totalPrice += $regionData['area'] * $regionData['perimeter'];
                }
            }
        }

        return $totalPrice;
    }

    /**
     * @param  array<array-key, array<array-key, string>>  $visited
     * @return array<array-key, int>
     */
    private function exploreRegion(int $row, int $col, array &$visited): array
    {
        $plantType = $this->map[$row][$col];
        $area = 0;
        $perimeter = 0;
        $stack = [[$row, $col]];
        $visited[$row][$col] = true;

        while (! empty($stack)) {
            [$currentRow, $currentCol] = array_pop($stack);
            $area++;

            $directions = [[0, 1], [1, 0], [0, -1], [-1, 0]];
            foreach ($directions as [$dr, $dc]) {
                $newRow = $currentRow + $dr;
                $newCol = $currentCol + $dc;

                if ($this->isInBounds($newRow, $newCol)) {
                    if ($this->map[$newRow][$newCol] === $plantType) {
                        if (! $visited[$newRow][$newCol]) {
                            $visited[$newRow][$newCol] = true;
                            $stack[] = [$newRow, $newCol];
                        }
                    } else {
                        $perimeter++;
                    }
                } else {
                    $perimeter++;
                }
            }
        }

        return ['area' => $area, 'perimeter' => $perimeter];
    }

    private function isInBounds(int $row, int $col): bool
    {
        return $row >= 0 && $row < $this->rows && $col >= 0 && $col < $this->cols;
    }
}

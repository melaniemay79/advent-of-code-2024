<?php

namespace AdventOfCode2024;

use RuntimeException;

class DayEight
{
    /**
     * @var array<int, mixed>
     */
    private array $input;

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

        $this->input = $input;

        $this->processInput();
    }

    private function processInput(): void
    {
        $this->input = array_map(function ($row) {
            if (is_string($row)) {
                return str_split($row);
            }

            return $row;
        }, $this->input);
    }

    /**
     * @return array<int|string, array<int, array<int, int>>>
     */
    public function findAntennas(): array
    {
        $antennas = [];
        /**
         * @var array<int, string> $line
         */
        foreach ($this->input as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char !== '.') {
                    if (! isset($antennas[$char])) {
                        $antennas[$char] = [];
                    }
                    $antennas[$char][] = [$x, $y];
                }
            }
        }

        return $antennas;
    }

    /**
     * @param  array<int|string, array<int, array<int, int>>>  $antennas
     * @return array<int, array<int, int>>
     */
    public function calculateAntinodes(array $antennas): array
    {
        $antinodePositions = [];

        foreach ($antennas as $freq => $positions) {
            $n = count($positions);
            for ($i = 0; $i < $n; $i++) {
                for ($j = 0; $j < $n; $j++) {
                    if ($i !== $j) {
                        [$x1, $y1] = $positions[$i];
                        [$x2, $y2] = $positions[$j];

                        $dx = $x2 - $x1;
                        $dy = $y2 - $y1;

                        $antinode1 = [$x1 - $dx, $y1 - $dy];
                        $antinode2 = [$x2 + $dx, $y2 + $dy];

                        $antinodePositions[] = $antinode1;
                        $antinodePositions[] = $antinode2;
                    }
                }
            }
        }

        return $antinodePositions;
    }

    public function countUniqueAntinodes(): int
    {
        $antennas = $this->findAntennas();
        $antinodePositions = $this->calculateAntinodes($antennas);

        if (! is_array($this->input) || empty($this->input) || ! is_array($this->input[0])) {
            return 0;
        }

        $uniqueAntinodes = [];
        foreach ($antinodePositions as [$x, $y]) {
            if ($x >= 0 && $x < count($this->input[0]) && $y >= 0 && $y < count($this->input)) {
                $uniqueAntinodes["$x,$y"] = true; // Use associative array to ensure uniqueness
            }
        }

        return count($uniqueAntinodes);
    }
}

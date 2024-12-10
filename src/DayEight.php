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
    private function calculateAntinodesPart2(array $antennas): array
    {
        $antinodePositions = [];

        if (! is_array($this->input) || empty($this->input) || ! is_array($this->input[0])) {
            return [];
        }

        $gridWidth = count($this->input[0]);
        $gridHeight = count($this->input);

        foreach ($antennas as $freq => $positions) {
            $n = count($positions);
            if ($n < 2) {
                continue;
            }

            for ($i = 0; $i < $n; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    [$x1, $y1] = $positions[$i];
                    [$x2, $y2] = $positions[$j];

                    $dx = $x2 - $x1;
                    $dy = $y2 - $y1;

                    $gcd = $this->gcd(abs($dx), abs($dy));
                    if ($gcd > 0) {
                        $stepX = $dx / $gcd;
                        $stepY = $dy / $gcd;

                        $minSteps = -max(
                            ceil($x1 / abs($stepX ?: 1)),
                            ceil($y1 / abs($stepY ?: 1))
                        );
                        $maxSteps = max(
                            ceil(($gridWidth - $x1) / abs($stepX ?: 1)),
                            ceil(($gridHeight - $y1) / abs($stepY ?: 1))
                        );

                        for ($k = $minSteps; $k <= $maxSteps; $k++) {
                            $x = $x1 + ($k * $stepX);
                            $y = $y1 + ($k * $stepY);

                            if ($x >= 0 && $x < $gridWidth &&
                                $y >= 0 && $y < $gridHeight &&
                                $x == (int) $x && $y == (int) $y) {
                                $antinodePositions[] = [(int) $x, (int) $y];
                            }
                        }
                    }
                }
            }

            foreach ($positions as $pos) {
                $antinodePositions[] = $pos;
            }
        }

        return array_unique($antinodePositions, SORT_REGULAR);
    }

    private function gcd(int $a, int $b): int
    {
        $a = abs($a);
        $b = abs($b);
        while ($b !== 0) {
            $temp = $b;
            $b = $a % $b;
            $a = $temp;
        }

        return $a;
    }

    /**
     * @param  array<int|string, array<int, array<int, int>>>  $antennas
     * @return array<int, array<int, int>>
     */
    public function calculateAntinodesPart1(array $antennas): array
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

    public function countUniqueAntinodes(bool $part2 = false): int
    {
        $antennas = $this->findAntennas();
        if ($part2) {
            $antinodePositions = $this->calculateAntinodesPart2($antennas);
        } else {
            $antinodePositions = $this->calculateAntinodesPart1($antennas);
        }

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

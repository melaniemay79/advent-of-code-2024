<?php

namespace AdventOfCode2024;

use RuntimeException;

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
     * @return array<string, int>
     */
    private function bfs(array $start): array
    {
        $unreachable = 10 ** 10;
        $states = [];
        $states["{$start[0]},{$start[1]}"] = 0;
        $toUpdate = [$start];

        while (! empty($toUpdate)) {
            $state = array_pop($toUpdate);
            $cost = $states["{$state[0]},{$state[1]}"];

            foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$dy, $dx]) {
                $newY = $state[0] + $dy;
                $newX = $state[1] + $dx;

                if ($newY < 0 || $newY >= $this->height ||
                    $newX < 0 || $newX >= $this->width ||
                    $this->map[$newY][$newX] === '#') {
                    continue;
                }

                $newKey = "$newY,$newX";
                $newCost = $cost + 1;

                if (! isset($states[$newKey]) || $newCost < $states[$newKey]) {
                    $states[$newKey] = $newCost;
                    $toUpdate[] = [$newY, $newX];
                }
            }
        }

        return $states;
    }

    /**
     * @param  array<int, int>  $loc
     * @return array<int, array<int, int>>
     */
    private function generateManhattanPoints(array $loc, int $dist): array
    {
        $points = [];
        [$i, $j] = $loc;

        for ($di = 0; $di < $dist; $di++) {
            $dj = $dist - $di;
            $points[] = [$i + $di, $j + $dj];
            $points[] = [$i + $dj, $j - $di];
            $points[] = [$i - $di, $j - $dj];
            $points[] = [$i - $dj, $j + $di];
        }

        return $points;
    }

    /**
     * @return array<int, int>
     */
    public function findCheats(int $savedTime): array
    {
        $endStates = $this->bfs($this->end);
        $startStates = $this->bfs($this->start);
        $fullCost = $startStates["{$this->end[0]},{$this->end[1]}"];
        $cheatsP1 = 0;
        $cheatsP2 = 0;

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                if ($this->map[$y][$x] !== '#') {
                    continue;
                }

                foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$dy, $dx]) {
                    $beforeY = $y + $dy; // Changed from y - dy
                    $beforeX = $x + $dx; // Changed from x - dx
                    $afterY = $y - $dy;  // Changed from y + dy
                    $afterX = $x - $dx;  // Changed from x + dx

                    $beforeKey = "$beforeY,$beforeX";
                    $afterKey = "$afterY,$afterX";

                    if (isset($startStates[$beforeKey]) && isset($endStates[$afterKey])) {
                        $savings = $fullCost - $endStates[$afterKey] - $startStates[$beforeKey] - 2;
                        if ($savings >= $savedTime) {
                            $cheatsP1++;
                        }
                    }
                }
            }
        }

        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                if ($this->map[$y][$x] === '#') {
                    continue;
                }

                $startKey = "$y,$x";
                if (! isset($startStates[$startKey])) {
                    continue;
                }

                for ($dist = 1; $dist <= 20; $dist++) {
                    foreach ($this->generateManhattanPoints([$y, $x], $dist) as $endPoint) {
                        [$ey, $ex] = $endPoint;

                        if ($ey < 0 || $ey >= $this->height ||
                            $ex < 0 || $ex >= $this->width ||
                            $this->map[$ey][$ex] === '#') {
                            continue;
                        }

                        $endKey = "$ey,$ex";
                        if (! isset($endStates[$endKey])) {
                            continue;
                        }

                        $savings = $fullCost - $endStates[$endKey] - $startStates[$startKey] - $dist;
                        if ($savings >= $savedTime) {
                            $cheatsP2++;
                        }
                    }
                }
            }
        }

        return [$cheatsP1, $cheatsP2];
    }
}

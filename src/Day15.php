<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day15
{
    /**
     * @var array<int, array<int, string>>
     */
    private array $grid;

    /**
     * @var array<int, string>
     */
    private array $moves;

    private int $robotX;

    private int $robotY;

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

    private function processInput(string $input, bool $double = false): void
    {
        [$gridStr, $movesStr] = explode("\n\n", trim($input));

        $lines = explode("\n", $gridStr);
        if ($double) {
            foreach ($lines as $y => $line) {
                $wideLine = '';
                foreach (str_split($line) as $char) {
                    switch ($char) {
                        case '#': $wideLine .= '##';
                            break;
                        case 'O': $wideLine .= '[]';
                            break;
                        case '.': $wideLine .= '..';
                            break;
                        case '@': $wideLine .= '@.';
                            break;
                        default: $wideLine .= $char.$char;
                    }
                }
                $this->grid[$y] = str_split($wideLine);
                if (($x = strpos($wideLine, '@')) !== false) {
                    $this->robotX = $x;
                    $this->robotY = $y;
                }
            }
        } else {
            foreach ($lines as $y => $line) {
                $this->grid[$y] = str_split($line);
                if (($x = strpos($line, '@')) !== false) {
                    $this->robotX = $x;
                    $this->robotY = $y;
                }
            }
        }

        $this->moves = str_split(str_replace("\n", '', $movesStr));
    }

    public function solve(bool $double = false): int
    {
        foreach ($this->moves as $move) {
            $this->processMove($move, $double);
        }

        return $this->calculateGPSSum();
    }

    private function processMove(string $move, bool $double = false): void
    {
        $dx = $dy = 0;

        switch ($move) {
            case '^': $dy = -1;
                break;
            case 'v': $dy = 1;
                break;
            case '<': $dx = -1;
                break;
            case '>': $dx = 1;
                break;
        }

        $newX = $this->robotX + $dx;
        $newY = $this->robotY + $dy;

        if ($this->canMove($newX, $newY, $dx, $dy, $double)) {
            $this->moveRobot($newX, $newY, $double);
        }
    }

    private function canMove(int $x, int $y, int $dx, int $dy, bool $double = false): bool
    {
        if (! isset($this->grid[$y][$x])) {
            return false;
        }

        $targetCell = $this->grid[$y][$x];

        if ($targetCell === '#') {
            return false;
        }

        if ($targetCell === '.' || $targetCell === '@') {
            return true;
        }

        if ($double) {
            if ($targetCell === '[' || $targetCell === ']') {
                $nextX = $x + $dx;
                $nextY = $y + $dy;

                if (! isset($this->grid[$nextY][$nextX])) {
                    return false;
                }

                $nextCell = $this->grid[$nextY][$nextX];
                if ($nextCell === '.') {
                    return true;
                }
                if ($nextCell === '[' || $nextCell === ']') {
                    return $this->canMove($nextX, $nextY, $dx, $dy);
                }
            }
        } else {
            if ($targetCell === 'O') {
                $nextX = $x + $dx;
                $nextY = $y + $dy;

                if (! isset($this->grid[$nextY][$nextX])) {
                    return false;
                }

                $nextCell = $this->grid[$nextY][$nextX];
                if ($nextCell === '.') {
                    return true;
                }
                if ($nextCell === 'O') {
                    return $this->canMove($nextX, $nextY, $dx, $dy);
                }
            }
        }

        return false;
    }

    private function moveRobot(int $newX, int $newY, bool $double = false): void
    {
        $dx = $newX - $this->robotX;
        $dy = $newY - $this->robotY;

        if ($double) {
            if ($this->grid[$newY][$newX] === '[' || $this->grid[$newY][$newX] === ']') {
                $boxPositions = [];
                $checkX = $newX;
                $checkY = $newY;

                while (isset($this->grid[$checkY][$checkX]) &&
                       ($this->grid[$checkY][$checkX] === '[' || $this->grid[$checkY][$checkX] === ']')) {
                    $boxPositions[] = [$checkX, $checkY];
                    $checkX += $dx;
                    $checkY += $dy;
                }

                for ($i = count($boxPositions) - 1; $i >= 0; $i--) {
                    [$boxX, $boxY] = $boxPositions[$i];
                    $this->grid[$boxY + $dy][$boxX + $dx] = ($boxX % 2 === 0) ? '[' : ']';
                    $this->grid[$boxY][$boxX] = '.';
                }
            }
        } else {
            if ($this->grid[$newY][$newX] === 'O') {
                $boxPositions = [];
                $checkX = $newX;
                $checkY = $newY;

                while (isset($this->grid[$checkY][$checkX]) && $this->grid[$checkY][$checkX] === 'O') {
                    $boxPositions[] = [$checkX, $checkY];
                    $checkX += $dx;
                    $checkY += $dy;
                }

                for ($i = count($boxPositions) - 1; $i >= 0; $i--) {
                    [$boxX, $boxY] = $boxPositions[$i];
                    $this->grid[$boxY + $dy][$boxX + $dx] = 'O';
                    $this->grid[$boxY][$boxX] = '.';
                }
            }
        }

        $this->grid[$newY][$newX] = '@';
        $this->grid[$this->robotY][$this->robotX] = '.';

        $this->robotX = $newX;
        $this->robotY = $newY;
    }

    private function calculateGPSSum(): int
    {
        $sum = 0;
        foreach ($this->grid as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($cell === 'O' || $cell === '[') {
                    $sum += (100 * $y + $x);
                }
            }
        }

        return $sum;
    }
}

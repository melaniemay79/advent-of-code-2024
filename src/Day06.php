<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day06
{
    /**
     * @var array<int, string>
     */
    private array $input;

    /**
     * @var array<int, string>
     */
    private array $output;

    /**
     * @var array<int, string>
     */
    private array $originalInput;

    /**
     * @var array<int, int>
     */
    private array $guardPosition;

    /**
     * @var array<string, string>
     */
    private array $guardDirections;

    /**
     * @var array<int, array<int, int>>
     */
    private array $positions;

    private string $currentDirection;

    private int $rows;

    private int $cols;

    private bool $isExit;

    private int $infiniteObstacles;

    private ?int $maxMovements;

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
        $this->originalInput = $input;
        $this->output = $input;

        $this->processInput();
    }

    private function processInput(): void
    {
        $this->rows = count($this->input);
        $this->cols = strlen($this->input[0]);
        $this->guardPosition = $this->startingPosition();
        $this->guardDirections = $this->guardDirections();
        $this->currentDirection = '^';
        $this->positions[] = $this->guardPosition;
        $this->isExit = false;
        $this->infiniteObstacles = 0;
        $this->maxMovements = null;
    }

    /**
     * @return array<string, string>
     */
    private function guardDirections(): array
    {
        return [
            '^' => 'up',
            'v' => 'down',
            '<' => 'left',
            '>' => 'right',
        ];
    }

    private function getDirection(string $direction): string
    {
        return $this->guardDirections[$direction];
    }

    /**
     * @return array<int, int>
     */
    private function startingPosition(): array
    {
        for ($row = 0; $row < $this->rows; $row++) {
            for ($col = 0; $col < $this->cols; $col++) {
                if ($this->input[$row][$col] === '^') {
                    return [$row, $col];
                }
            }
        }

        return [];
    }

    /**
     * @return array{
     *     guardPositions: int,
     *     output: array<int, string>,
     *     positions: array<int, array<int, int>>
     * }
     */
    public function predictGuardMovements(): array
    {
        while (! $this->isExit) {
            $this->move($this->guardPosition[0], $this->guardPosition[1], $this->getDirection($this->currentDirection));
        }

        return [
            'guardPositions' => count(array_unique($this->positions, SORT_REGULAR)),
            'output' => $this->output,
            'positions' => $this->positions,
        ];
    }

    public function move(int $row, int $col, string $direction): void
    {
        $checkBoard = $this->checkBoard($row, $col);

        switch ($direction) {
            case 'up':
                if ($checkBoard[0] === 'exit') {
                    $this->exitBoard();
                } elseif ($checkBoard[0] === '#') {
                    $this->move($row, $col, 'right');
                } else {
                    $this->moveUp($row, $col);
                }
                break;
            case 'down':
                if ($checkBoard[1] === 'exit') {
                    $this->exitBoard();
                } elseif ($checkBoard[1] === '#') {
                    $this->move($row, $col, 'left');
                } else {
                    $this->moveDown($row, $col);
                }
                break;
            case 'left':
                if ($checkBoard[2] === 'exit') {
                    $this->exitBoard();
                } elseif ($checkBoard[2] === '#') {
                    $this->move($row, $col, 'up');
                } else {
                    $this->moveLeft($row, $col);
                }
                break;
            case 'right':
                if ($checkBoard[3] === 'exit') {
                    $this->exitBoard();
                } elseif ($checkBoard[3] === '#') {
                    $this->move($row, $col, 'down');
                } else {
                    $this->moveRight($row, $col);
                }
                break;
        }
    }

    private function moveUp(int $row, int $col): void
    {
        $this->currentDirection = '^';

        $this->update($row - 1, $col);
    }

    private function moveDown(int $row, int $col): void
    {
        $this->currentDirection = 'v';

        $this->update($row + 1, $col);
    }

    private function moveLeft(int $row, int $col): void
    {
        $this->currentDirection = '<';

        $this->update($row, $col - 1);
    }

    private function moveRight(int $row, int $col): void
    {
        $this->currentDirection = '>';

        $this->update($row, $col + 1);
    }

    private function update(int $row, int $col): void
    {
        $this->positions[] = [$row, $col];

        $this->guardPosition = [$row, $col];

        $this->output[$row][$col] = 'X';

        if ($this->maxMovements !== null) {
            if ($this->isInfinite()) {
                $this->infiniteObstacles++;
                $this->exitBoard();
            }
        }
    }

    private function isInfinite(): bool
    {
        return $this->maxMovements !== null && count($this->positions) > $this->maxMovements;
    }

    private function exitBoard(): void
    {
        $this->isExit = true;

        if ($this->maxMovements !== null) {
            $this->positions = [];
        }
    }

    /**
     * @return array<int, string>
     */
    private function checkBoard(int $row, int $col): array
    {
        $up = $row == 0 ? 'exit' : $this->input[$row - 1][$col];
        $down = $row == $this->rows - 1 ? 'exit' : $this->input[$row + 1][$col];
        $left = $col == 0 ? 'exit' : $this->input[$row][$col - 1];
        $right = $col == $this->cols - 1 ? 'exit' : $this->input[$row][$col + 1];

        return [$up, $down, $left, $right];
    }

    private function addObstacle(int $row, int $col): bool
    {
        if ($this->input[$row][$col] === '.') {
            $this->input[$row][$col] = '#';

            return true;
        }

        return false;
    }

    private function resetBoard(): void
    {
        $this->input = $this->originalInput;
        $this->output = $this->originalInput;
        $this->guardPosition = $this->startingPosition();
        $this->currentDirection = '^';
        $this->positions = [];
        $this->isExit = false;
    }

    private function checkCoordinates(int $row, int $col): string
    {
        if ($this->input[$row][$col] === '.') {
            return 'empty';
        }

        if ($this->input[$row][$col] === '#') {
            return 'obstacle';
        }

        if ($this->input[$row][$col] === '^') {
            return 'guard';
        }

        return '';
    }

    public function createInfiniteLoops(): int
    {
        $predictedGuardMovements = $this->predictGuardMovements();
        $positions = array_unique($predictedGuardMovements['positions'], SORT_REGULAR);
        $this->maxMovements = count($predictedGuardMovements['positions']) * 2;

        foreach ($positions as $position) {
            if ($this->checkCoordinates($position[0], $position[1]) === 'empty') {
                if ($this->addObstacle($position[0], $position[1])) {
                    $predictedGuardMovements = $this->predictGuardMovements();
                    $this->resetBoard();
                }
            }
        }

        return $this->infiniteObstacles;
    }
}

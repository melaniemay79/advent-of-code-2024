<?php

namespace AdventOfCode2024;

class DayFour
{
    private string $input;

    /**
     * @var array<int, string>
     */
    private array $lines;

    /**
     * @param  string  $file
     */
    public function __construct($file)
    {
        $input = file_get_contents($file);

        if ($input === false) {
            exit('Failed to read input file');
        } else {
            $this->input = trim($input);
        }

        $this->processInput();
    }

    private function processInput(): void
    {
        $len = strpos($this->input, "\n") ?: strlen($this->input);
        for ($i = 0; $i < strlen($this->input); $i += $len + 1) {
            $line = substr($this->input, $i, $len);
            if ($line) {
                $this->lines[] = $line;
            }
        }
    }

    public function find(): int
    {
        $count = 0;
        $cols = strlen($this->lines[0]);

        $directions = [
            [0, 1],   // right
            [0, -1],  // left
            [1, 0],   // down
            [-1, 0],  // up
            [1, 1],   // diagonal down-right
            [-1, -1], // diagonal up-left
            [1, -1],  // diagonal down-left
            [-1, 1],   // diagonal up-right
        ];

        foreach ($this->lines as $row => $line) {
            for ($col = 0; $col < $cols; $col++) {
                foreach ($directions as $direction) {
                    if ($this->check($this->lines, 'XMAS', $row, $col, $direction[0], $direction[1])) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * @param  string[]  $lines
     */
    private function check(array $lines, string $word, int $startRow, int $startCol, int $dirRow, int $dirCol): bool
    {
        $len = strlen($word);
        for ($i = 0; $i < $len; $i++) {
            $row = $startRow + $i * $dirRow;
            $col = $startCol + $i * $dirCol;
            if ($row < 0 || $row >= count($lines) || $col < 0 || $col >= strlen($lines[$row]) || $lines[$row][$col] !== $word[$i]) {
                return false;
            }
        }

        return true;
    }

    public function findX(): int
    {
        $count = 0;
        $rows = count($this->lines);
        $cols = strlen($this->lines[0]);

        for ($row = 0; $row < $rows - 2; $row++) {
            for ($col = 0; $col < $cols - 2; $col++) {
                if ($this->checkX($this->lines, $row, $col)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * @param  string[]  $lines
     */
    private function checkX(array $lines, int $row, int $col): bool
    {
        $pos1a = $lines[$row][$col];
        $pos1b = $lines[$row + 2][$col + 2];

        $pos2a = $lines[$row][$col + 2];
        $pos2b = $lines[$row + 2][$col];

        if ($lines[$row + 1][$col + 1] !== 'A') {
            return false;
        }

        if (
            (($pos1a == 'M' && $pos1b == 'S') || ($pos1a == 'S' && $pos1b == 'M')) &&
            (($pos2a == 'M' && $pos2b == 'S') || ($pos2a == 'S' && $pos2b == 'M'))
        ) {
            return true;
        }

        return false;
    }
}

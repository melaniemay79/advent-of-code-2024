<?php

namespace AdventOfCode2024;

use RuntimeException;

class DayTen
{
    /**
     * @var array<int, string>
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
            throw new RuntimeException('Failed to read input file');
        }

        $this->processInput($input);
    }

    /**
     * @param  array<int, string>  $input
     */
    private function processInput($input): void
    {
        $this->input = $input;

        $this->getTrailheads();
    }

    private function getTrailheads(): void
    {
        $map = $this->input;

        $trailheads = [];

        foreach ($this->input as $index => $string) {
            $pos = strpos($string, '0');
            while ($pos !== false) {
                $trailheads[] = ['row' => $index, 'col' => $pos];
                $pos = strpos($string, '0', $pos + 1);
            }
        }

        dd($trailheads);
    }
}

<?php

namespace AdventOfCode2024;

use RuntimeException;

class DayNine
{
    private string $input;

    /**
     * @var array<int, string>
     */
    private array $diskMap;

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
        } else {
            $this->input = $input;
        }

        $this->processInput();
    }

    private function processInput(): void
    {
        $this->diskMap = $this->formatDiskMap();
        $this->freeDiskSpace();
    }

    /**
     * @return array<int, string>
     */
    private function formatDiskMap(): array
    {
        $formattedDiskMap = [];
        $i = 0;

        $totalLength = 0;
        foreach (str_split($this->input) as $n => $item) {
            $count = (int) $item;
            $totalLength += $count;
        }

        $formattedDiskMap = array_fill(0, $totalLength, '.');
        $currentIndex = 0;

        foreach (str_split($this->input) as $n => $item) {
            $count = (int) $item;
            if ($n % 2 === 0) {
                for ($j = 0; $j < $count; $j++) {
                    $formattedDiskMap[$currentIndex++] = (string) $i;
                }
                $i++;
            } else {
                $currentIndex += $count;
            }
        }

        return $formattedDiskMap;
    }

    private function freeDiskSpace(): void
    {
        $length = count($this->diskMap);
        $rightmostNumPos = -1;

        for ($i = 0; $i < $length; $i++) {
            if (is_numeric($this->diskMap[$i])) {
                $rightmostNumPos = $i;
            }
        }

        while ($rightmostNumPos > 0) {
            $leftmostDotPos = -1;
            for ($i = 0; $i < $rightmostNumPos; $i++) {
                if ($this->diskMap[$i] === '.') {
                    $leftmostDotPos = $i;
                    break;
                }
            }

            if ($leftmostDotPos !== -1 && $leftmostDotPos < $rightmostNumPos) {
                $this->diskMap[$leftmostDotPos] = $this->diskMap[$rightmostNumPos];
                $this->diskMap[$rightmostNumPos] = '.';

                $rightmostNumPos--;
                while ($rightmostNumPos >= 0 && ! is_numeric($this->diskMap[$rightmostNumPos])) {
                    $rightmostNumPos--;
                }
            } else {
                break;
            }
        }
    }

    public function checkSum(): int
    {
        return array_sum(array_map(
            fn ($k, $v) => $v === '.' ? 0 : (int) $k * (int) $v,
            array_keys($this->diskMap),
            $this->diskMap
        ));
    }
}

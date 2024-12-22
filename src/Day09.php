<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day09
{
    private string $input;

    /**
     * @var array<int, int|string>
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
        $this->freeDiskSpace();

        return array_sum(array_map(
            fn ($k, $v) => $v === '.' ? 0 : (int) $k * (int) $v,
            array_keys($this->diskMap),
            $this->diskMap
        ));
    }

    private function processDiskMap(string $input): void
    {
        $this->diskMap = [];
        $id = 0;

        for ($i = 1; $i <= strlen($input); $i++) {
            $value = intval($input[$i - 1]);
            for ($j = 0; $j < $value; $j++) {
                if ($i % 2 == 0) {
                    $this->diskMap[] = -1;
                } else {
                    $this->diskMap[] = $id;
                }
            }
            if ($i % 2 != 0) {
                $id++;
            }
        }
    }

    public function calculateChecksum(): int
    {
        $this->processDiskMap($this->input);
        $diskMap = $this->diskMap;
        $id = count(array_unique(array_filter($diskMap, fn ($x) => $x >= 0))) - 1;
        $maxIdIdx = count($diskMap);

        while ($id >= 0) {
            while ($diskMap[$maxIdIdx - 1] != $id) {
                $maxIdIdx--;
            }

            $minIdIdx = $maxIdIdx - 1;
            while ($minIdIdx > 0 && $diskMap[$minIdIdx - 1] == $id) {
                $minIdIdx--;
            }
            $idSize = $maxIdIdx - $minIdIdx;

            $minEmptySlotIdx = 0;
            while (true) {
                while ($minEmptySlotIdx < count($diskMap) && $diskMap[$minEmptySlotIdx] != -1) {
                    $minEmptySlotIdx++;
                }

                $maxEmptySlotIdx = $minEmptySlotIdx + 1;
                while ($maxEmptySlotIdx < count($diskMap) && $diskMap[$maxEmptySlotIdx] == -1) {
                    $maxEmptySlotIdx++;
                }
                $emptySlotSize = $maxEmptySlotIdx - $minEmptySlotIdx;

                if ($emptySlotSize >= $idSize) {
                    break;
                }
                $minEmptySlotIdx += $emptySlotSize;
                if ($minEmptySlotIdx >= $minIdIdx) {
                    break;
                }
            }

            if ($minEmptySlotIdx < $minIdIdx) {
                for ($i = 0; $i < $idSize; $i++) {
                    $diskMap[$minEmptySlotIdx + $i] = $id;
                }
                for ($i = $minIdIdx; $i < $maxIdIdx; $i++) {
                    $diskMap[$i] = -1;
                }
            }

            $id--;
        }

        $diskMap = array_map(fn ($x) => $x == -1 ? 0 : $x, $diskMap);

        $checksum = 0;
        foreach ($diskMap as $i => $v) {
            $checksum += (int) $i * (int) $v;
        }

        return $checksum;
    }
}

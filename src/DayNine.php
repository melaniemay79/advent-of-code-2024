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

    public function checkSum(bool $wholeFiles = false): int
    {
        if ($wholeFiles) {
            $this->freeDiskBlocks();
        } else {
            $this->freeDiskSpace();
        }

        return array_sum(array_map(
            fn ($k, $v) => $v === '.' ? 0 : (int) $k * (int) $v,
            array_keys($this->diskMap),
            $this->diskMap
        ));
    }

    private function compactFiles(): void
    {
        $length = count($this->diskMap);
        $fileSizes = [];
        $currentFileId = -1;

        // Step 1: Identify files and their sizes
        for ($i = 0; $i < $length; $i++) {
            if (is_numeric($this->diskMap[$i])) {
                if ($currentFileId == -1 || $this->diskMap[$i] != $currentFileId) {
                    $currentFileId = $this->diskMap[$i];
                    $fileSizes[$currentFileId] = 0; // Initialize size for this file ID
                }
                $fileSizes[$currentFileId]++;
            } else {
                $currentFileId = -1; // Reset on dot
            }
        }

        // Step 2: Move files in order of decreasing file ID
        krsort($fileSizes); // Sort file sizes in descending order of file ID

        foreach ($fileSizes as $fileId => $size) {
            // Step 3: Find a span of free space large enough to fit the file
            $freeSpaceStart = -1;
            $freeSpaceLength = 0;

            for ($i = 0; $i < $length; $i++) {
                if ($this->diskMap[$i] === '.') {
                    if ($freeSpaceStart === -1) {
                        $freeSpaceStart = $i; // Start of free space
                    }
                    $freeSpaceLength++;
                } else {
                    // If we hit a file, check if we have enough space
                    if ($freeSpaceLength >= $size) {
                        // Move the file to the leftmost free space
                        $this->moveFileToFreeSpace($fileId, $size, $freeSpaceStart);
                        break; // Move to the next file
                    }
                    // Reset free space tracking
                    $freeSpaceStart = -1;
                    $freeSpaceLength = 0;
                }
            }

            // Final check at the end of the disk
            if ($freeSpaceLength >= $size) {
                $this->moveFileToFreeSpace($fileId, $size, $freeSpaceStart);
            }
        }
    }

    private function moveFileToFreeSpace(int $fileId, int $size, int $freeSpaceStart): void
    {
        // Step 4: Move the file to the leftmost free space
        $length = count($this->diskMap);
        $fileEnd = $freeSpaceStart + $size;

        // Move the file ID into the free space
        for ($i = 0; $i < $size; $i++) {
            $this->diskMap[$freeSpaceStart + $i] = (string) $fileId;
        }

        // Clear the original file space
        for ($i = 0; $i < $size; $i++) {
            if ($fileEnd + $i < $length) {
                $this->diskMap[$fileEnd + $i] = '.'; // Clear the original space
            }
        }
    }

    private function freeDiskBlocks(): void
    {
        $length = count($this->diskMap);
        $rightmostNumPos = -1;

        for ($i = 0; $i < $length; $i++) {
            if (is_numeric($this->diskMap[$i])) {
                $rightmostNumPos = $i;
            }
        }

        while ($rightmostNumPos > 0) {
            $rightmostValue = $this->diskMap[$rightmostNumPos];
            $count = 0;

            // Count how many of the rightmost value there are
            for ($i = $rightmostNumPos; $i >= 0; $i--) {
                if ($this->diskMap[$i] == $rightmostValue) {
                    $count++;
                } else {
                    break;
                }
            }

            $leftmostDotPos = -1;
            $space = 0;
            for ($i = 0; $i < $rightmostNumPos; $i++) {
                for ($j = 0; $j < $count; $j++) {
                    if ($this->diskMap[$i + $j] !== '.') {
                        break;
                    }
                    $space++;
                }

                if ($this->diskMap[$i] === '.' && $space >= $count) {
                    $leftmostDotPos = $i;
                    break;
                }
            }

            dump('space: '.$space, 'count: '.$count);
            if ($space < $count) {
                $rightmostNumPos = $rightmostNumPos - $count;
                break;
            }

            dump(implode('', $this->diskMap), $rightmostNumPos, $leftmostDotPos);

            if ($leftmostDotPos !== -1 && $leftmostDotPos < $rightmostNumPos) {
                for ($i = 0; $i < $count; $i++) {
                    $this->diskMap[$leftmostDotPos + $i] = $this->diskMap[$rightmostNumPos - $i];
                    $this->diskMap[$rightmostNumPos - $i] = '.';
                }

                $rightmostNumPos--;
                while ($rightmostNumPos >= 0 && ! is_numeric($this->diskMap[$rightmostNumPos])) {
                    $rightmostNumPos--;
                }
            } else {
                break;
            }
            $rightmostNumPos = $rightmostNumPos - $count;
            dump('rightmostNumPos: '.$this->diskMap[$rightmostNumPos], 'count: '.$count);
            dump(implode('', $this->diskMap), $rightmostNumPos, $leftmostDotPos);
        }
    }
}

<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day09
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

    public function checkSum(): int
    {
        $this->freeDiskSpace();

        return array_sum(array_map(
            fn ($k, $v) => $v === '.' ? 0 : (int) $k * (int) $v,
            array_keys($this->diskMap),
            $this->diskMap
        ));
    }

    public function formatDiskBlocks(): int
    {
        $input = $this->input;
        $input = str_split($input);
        $disk = [];

        $n = 0;

        foreach ($input as $k => $v) {
            if ($k % 2 === 0) {
                $disk[] = [
                    'id' => $n,
                    'size' => (int) $v,
                ];
                $n++;
            } else {
                if ($v === '0') {
                    continue;
                }

                $disk[] = [
                    'id' => null,
                    'size' => (int) $v,
                ];
            }
        }

        $z = array_key_last($disk);

        while ($z > 0) {
            for ($a = 0; $a < $z; $a++) {
                if ($disk[$a]['id'] === null) {
                    $space = (int) $disk[$a]['size'] - (int) $disk[$z]['size'];
                    if ($space < 0) {
                        $a++;

                        continue;
                    } else {
                        $replace = $disk[$z];
                        $disk[$z]['id'] = null;
                        if ($space > 0) {
                            $space = [
                                $replace,
                                [
                                    'id' => null,
                                    'size' => $space,
                                ],
                            ];
                            $z++;
                        } else {
                            $space = [$replace];
                        }
                        $disk = array_merge(
                            array_slice($disk, 0, $a),
                            $space,
                            array_slice($disk, $a + 1)
                        );
                    }
                    break;
                }
            }

            $z--;
        }

        $sum = 0;

        $diskStr = '';
        foreach ($disk as $k => $v) {
            for ($i = 0; $i < $v['size']; $i++) {
                if ($v['id'] === null) {
                    $output = '.';
                } else {
                    $output = $v['id'];
                }
                $diskStr .= $output;
            }
        }

        $sum = array_sum(array_map(
            fn ($k, $v) => $v === '.' ? 0 : (int) $k * (int) $v,
            array_keys(str_split($diskStr)),
            str_split($diskStr)
        ));

        return $sum;
    }
}

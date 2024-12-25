<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day12
{
    /**
     * @var array<int|string, string>
     */
    private array $data = [];

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

    private function processInput(string $input): void
    {
        $lines = explode("\n", trim($input));

        foreach ($lines as $i => $line) {
            for ($j = 0; $j < strlen($line); $j++) {
                $this->data["$i,$j"] = $line[$j];
            }
        }
    }

    /**
     * @param  array<int, int>  $p1
     * @param  array<int, int>  $p2
     * @return array<int, int>
     */
    private function add(array $p1, array $p2): array
    {
        return [$p1[0] + $p2[0], $p1[1] + $p2[1]];
    }

    /**
     * @param  array<array-key, array<array-key, array<int, int>>>  $sets
     */
    private function totalSides(array &$sets): int
    {
        $sides = [];
        while (! empty($sets)) {
            $pair = array_pop($sets);
            [$loc, $out] = $pair;
            $side = [$pair];
            [$di, $dj] = $out;
            $right = [$dj, -$di];
            $left = [-$dj, $di];

            $rloc = $this->add($loc, $right);
            while (isset($sets[implode(',', $rloc).','.implode(',', $out)])) {
                unset($sets[implode(',', $rloc).','.implode(',', $out)]);
                $side[] = [$rloc, $out];
                $rloc = $this->add($rloc, $right);
            }

            $lloc = $this->add($loc, $left);
            while (isset($sets[implode(',', $lloc).','.implode(',', $out)])) {
                unset($sets[implode(',', $lloc).','.implode(',', $out)]);
                $side[] = [$lloc, $out];
                $lloc = $this->add($lloc, $left);
            }
            $sides[] = $side;
        }

        return count($sides);
    }

    /**
     * @return array<string, int>
     */
    public function calculateTotalPrice(): array
    {
        $dirs = [[0, 1], [1, 0], [0, -1], [-1, 0]];
        $areas = [];
        $a = [];
        $b = [];
        $part1 = 0;
        $part2 = 0;

        foreach ($this->data as $z => $c) {
            if (isset($areas[$z])) {
                continue;
            }

            $loc = array_map('intval', explode(',', (string) $z));
            $new = [$z => true];
            $b[] = &$new;
            $a[$z] = &$new;
            $s = [$loc];
            $border = [];

            while (! empty($s)) {
                $nloc = array_pop($s);
                foreach ($dirs as $d) {
                    $u = $this->add($nloc, $d);
                    $uStr = implode(',', $u);

                    if (isset($this->data[$uStr]) && $this->data[$uStr] === $c) {
                        if (! isset($areas[$uStr])) {
                            $s[] = $u;
                            $a[$uStr] = &$new;
                            $new[$uStr] = true;
                            $areas[$uStr] = true;
                        }
                    } else {
                        $borderKey = implode(',', $nloc).','.implode(',', $d);
                        $border[$borderKey] = [$nloc, $d];
                    }
                }
            }

            $part1 += count($border) * count($new);
            $part2 += $this->totalSides($border) * count($new);
        }

        return ['part1' => $part1, 'part2' => $part2];
    }
}

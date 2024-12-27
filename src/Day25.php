<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day25
{
    /**
     * @var array<int<0, max>, array<int, int>>
     */
    private array $keys = [];

    /**
     * @var array<int<0, max>, array<int, int>>
     */
    private array $locks = [];

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
        $schematics = explode("\n\n", $input);
        $this->processSchematics($schematics);
    }

    /**
     * @param  array<array-key, string>  $schematics
     */
    private function processSchematics($schematics): void
    {
        $locks = [];
        $keys = [];

        foreach ($schematics as $schematic) {
            $lines = explode("\n", $schematic);
            if ($lines[0] == '#####') {
                $locks[] = $lines;
            } else {
                $keys[] = $lines;
            }
        }

        $this->processLocks($locks);
        $this->processKeys($keys);
    }

    /**
     * @param  array<int, array<int, string>>  $locks
     */
    private function processLocks($locks): void
    {
        $processedLocks = [];
        $ret = [];
        $i = 0;
        foreach ($locks as $lock) {
            foreach ($lock as $line) {
                $processedLocks[$i][] = str_split($line);
            }
            $i++;
        }

        $h = count($processedLocks[0]);
        $d = count($processedLocks[0][0]);

        foreach ($processedLocks as $k => $lock) {
            for ($i = 0; $i < $d; $i++) {
                $x = 0;
                for ($j = 0; $j < $h; $j++) {
                    if ($lock[$j][$i] == '#') {
                        $x++;
                    }
                }
                $ret[$k][] = $x - 1;
            }
        }
        $this->locks = $ret;
    }

    /**
     * @param  array<int, array<int, string>>  $keys
     */
    private function processKeys($keys): void
    {
        $processedKeys = [];
        $ret = [];
        $i = 0;
        foreach ($keys as $key) {
            foreach ($key as $line) {
                $processedKeys[$i][] = str_split($line);
            }
            $i++;
        }

        $h = count($processedKeys[0]);
        $d = count($processedKeys[0][0]);

        foreach ($processedKeys as $k => $key) {
            for ($i = 0; $i < $d; $i++) {
                $x = 0;
                for ($j = 0; $j < $h; $j++) {
                    if ($key[$j][$i] == '#') {
                        $x++;
                    }
                }
                $ret[$k][] = $x - 1;
            }
        }
        $this->keys = $ret;
    }

    private function checkKey(int $key, int $lock): bool
    {
        return $key + $lock <= 5;
    }

    public function part1(): int
    {
        $total = 0;
        $pass = true;

        $pos = count($this->keys[0]);

        foreach ($this->keys as $key) {
            foreach ($this->locks as $lock) {
                for ($i = 0; $i < $pos; $i++) {
                    if ($this->checkKey($key[$i], $lock[$i])) {
                        $pass = true;
                    } else {
                        $pass = false;
                        break;
                    }
                }
                if ($pass) {
                    $total++;
                }
            }
        }

        return $total;
    }
}

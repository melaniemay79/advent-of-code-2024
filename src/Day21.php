<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day21
{
    private string $input;

    /** @var array<string, array<int, int>|string> */
    private array $numPad;

    /**
     * @var array<string, array<int, int>|string>
     */
    private array $dirPad;

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

        $this->initializePads();

        $this->input = trim($this->input);
    }

    private function initializePads(): void
    {
        $numPadLines = trim('789
456
123
.0A
');
        $dirPadLines = trim('
.^A
<v>
');

        $this->numPad = [];
        $this->dirPad = [];

        foreach (explode("\n", $numPadLines) as $i => $line) {
            foreach (str_split($line) as $j => $c) {
                if ($c !== '.') {
                    $this->numPad["$i,$j"] = $c;
                    $this->numPad[$c] = [$i, $j];
                }
            }
        }

        foreach (explode("\n", $dirPadLines) as $i => $line) {
            foreach (str_split($line) as $j => $c) {
                if ($c !== '.') {
                    $this->dirPad["$i,$j"] = $c;
                    $this->dirPad[$c] = [$i, $j];
                }
            }
        }
    }

    /**
     * @param  array<string, array<int, int>|string>  $pad
     */
    private function step(string $source, string $target, array $pad): string
    {
        $targetPos = $pad[$target];
        $sourcePos = $pad[$source];

        if (! is_array($targetPos) || ! is_array($sourcePos)) {
            return '';
        }

        [$ti, $tj] = $targetPos;
        [$si, $sj] = $sourcePos;
        $di = (int) $ti - (int) $si;
        $dj = (int) $tj - (int) $sj;

        $vert = str_repeat('v', max(0, $di)).str_repeat('^', max(0, -$di));
        $horiz = str_repeat('>', max(0, $dj)).str_repeat('<', max(0, -$dj));

        if ($dj > 0 && isset($pad["$ti,$sj"])) {
            return $vert.$horiz.'A';
        }
        if (isset($pad["$si,$tj"])) {
            return $horiz.$vert.'A';
        }
        if (isset($pad["$ti,$sj"])) {
            return $vert.$horiz.'A';
        }

        return '';
    }

    /**
     * @param  array<string, array<int, int>|string>  $pad
     */
    private function routes(string $path, array $pad): string
    {
        $out = [];
        $start = 'A';
        foreach (str_split($path) as $end) {
            $out[] = $this->step($start, $end, $pad);
            $start = $end;
        }

        return implode('', $out);
    }

    public function part1(): int
    {
        $lines = explode("\n", trim($this->input));
        $numRoutes = array_map(fn ($line) => $this->routes($line, $this->numPad), $lines);
        $radRoutes = array_map(fn ($route) => $this->routes($route, $this->dirPad), $numRoutes);
        $coldRoutes = array_map(fn ($route) => $this->routes($route, $this->dirPad), $radRoutes);

        return array_sum(array_map(
            fn ($route, $line) => strlen($route) * intval(substr($line, 0, -1)),
            $coldRoutes,
            $lines
        ));
    }

    public function part2(): int
    {
        $lines = explode("\n", trim($this->input));
        $numRoutes = array_map(fn ($line) => $this->routes($line, $this->numPad), $lines);

        $robotRoutes = array_map(fn ($route) => [$route => 1], $numRoutes);

        for ($i = 0; $i < 25; $i++) {
            $newRoutes = [];
            foreach ($robotRoutes as $routeCounter) {
                $newRoute = [];
                foreach ($routeCounter as $subRoute => $qty) {
                    $newCounts = $this->routes2($subRoute, $this->dirPad);
                    foreach ($newCounts as $k => $v) {
                        $newCounts[$k] *= $qty;
                    }
                    foreach ($newCounts as $k => $v) {
                        $newRoute[$k] = ($newRoute[$k] ?? 0) + $v;
                    }
                }
                $newRoutes[] = $newRoute;
            }
            $robotRoutes = $newRoutes;
        }

        return array_sum(array_map(
            fn ($route, $line) => $this->routeLen($route) * intval(substr($line, 0, -1)),
            $robotRoutes,
            $lines
        ));
    }

    /**
     * @param  array<string, array<int, int>|string>  $pad
     * @return array<string, int>
     */
    private function routes2(string $path, array $pad): array
    {
        $out = [];
        $start = 'A';
        foreach (str_split($path) as $end) {
            $step = $this->step($start, $end, $pad);
            $out[$step] = ($out[$step] ?? 0) + 1;
            $start = $end;
        }

        return $out;
    }

    /**
     * @param  array<string, int>  $route
     */
    private function routeLen(array $route): int
    {
        return array_sum(array_map(
            fn ($k, $v) => strlen($k) * $v,
            array_keys($route),
            array_values($route)
        ));
    }
}

<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day15
{
    /**
     * @var array<int, string>
     */
    private array $data;

    public function __construct(string $file)
    {
        if (! file_exists($file)) {
            throw new RuntimeException('File not found');
        }

        $input = file_get_contents($file);

        if ($input === false) {
            exit('Failed to read input file');
        }

        $this->data = explode("\n\n", trim($input));
    }

    private function solve2(): void
    {
        $a = explode("\n", $this->data[0]);
        $b = str_replace("\n", '', $this->data[1]);
        $n = count($a);
        $m = strlen($a[0]) * 2;

        for ($i = 0; $i < $n; $i++) {
            $na = [];
            $chars = is_string($a[$i]) ? str_split($a[$i]) : $a[$i];
            foreach ($chars as $c) {
                switch ($c) {
                    case '#':
                        $na[] = '#';
                        $na[] = '#';
                        break;
                    case 'O':
                        $na[] = '[';
                        $na[] = ']';
                        break;
                    case '.':
                        $na[] = '.';
                        $na[] = '.';
                        break;
                    default:
                        $na[] = '@';
                        $na[] = '.';
                }
            }
            $a[$i] = $na;
        }

        $sx = $sy = 0;
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                if ($a[$i][$j] === '@') {
                    $sx = $i;
                    $sy = $j;
                    $a[$i][$j] = '.';
                }
            }
        }

        $dx = [0, -1, 0, 1];
        $dy = [-1, 0, 1, 0];

        foreach (str_split($b) as $mv) {
            $d = match ($mv) {
                '^' => 1,
                '<' => 0,
                '>' => 2,
                default => 3,
            };

            if ($this->trypush($a, $sx, $sy, $dx[$d], $dy[$d])) {
                $this->push($a, $sx, $sy, $dx[$d], $dy[$d]);
                $sx += $dx[$d];
                $sy += $dy[$d];
            }
        }

        $ans = 0;
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                if ($a[$i][$j] === '[') {
                    $ans += 100 * $i + $j;
                }
            }
        }
        echo $ans."\n";
    }

    /**
     * @param  array<int, array<int, string>>  $a
     */
    private function trypush(array &$a, int $sx, int $sy, int $dx, int $dy): bool
    {
        $nx = $sx + $dx;
        $ny = $sy + $dy;

        if ($a[$nx][$ny] === '#') {
            return false;
        }
        if ($a[$nx][$ny] === '.') {
            return true;
        }
        if ($dy === 0) {
            if ($a[$nx][$ny] === ']') {
                return $this->trypush($a, $nx, $ny, $dx, $dy) &&
                       $this->trypush($a, $nx, $ny - 1, $dx, $dy);
            }
            if ($a[$nx][$ny] === '[') {
                return $this->trypush($a, $nx, $ny, $dx, $dy) &&
                       $this->trypush($a, $nx, $ny + 1, $dx, $dy);
            }
        }
        if ($dy === -1) { // push left
            if ($a[$nx][$ny] === ']') {
                return $this->trypush($a, $nx, $ny - 1, $dx, $dy);
            }
        }
        if ($dy === 1) { // push right
            if ($a[$nx][$ny] === '[') {
                return $this->trypush($a, $nx, $ny + 1, $dx, $dy);
            }
        }

        return false;
    }

    /**
     * @param  array<int, array<int, string>>  $a
     */
    private function push(array &$a, int $sx, int $sy, int $dx, int $dy): void
    {
        $nx = $sx + $dx;
        $ny = $sy + $dy;

        if ($a[$nx][$ny] === '#') {
            return;
        }
        if ($a[$nx][$ny] === '.') {
            [$a[$sx][$sy], $a[$nx][$ny]] = [$a[$nx][$ny], $a[$sx][$sy]];

            return;
        }
        if ($dy === 0) {
            if ($a[$nx][$ny] === ']') {
                $this->push($a, $nx, $ny, $dx, $dy);
                $this->push($a, $nx, $ny - 1, $dx, $dy);
                [$a[$sx][$sy], $a[$nx][$ny]] = [$a[$nx][$ny], $a[$sx][$sy]];

                return;
            }
            if ($a[$nx][$ny] === '[') {
                $this->push($a, $nx, $ny, $dx, $dy);
                $this->push($a, $nx, $ny + 1, $dx, $dy);
                [$a[$sx][$sy], $a[$nx][$ny]] = [$a[$nx][$ny], $a[$sx][$sy]];

                return;
            }
        }
        if ($dy === -1) { // push left
            if ($a[$nx][$ny] === ']') {
                $this->push($a, $nx, $ny - 1, $dx, $dy);
                [$a[$nx][$ny - 1], $a[$nx][$ny], $a[$sx][$sy]] =
                    [$a[$nx][$ny], $a[$sx][$sy], $a[$nx][$ny - 1]];

                return;
            }
        }
        if ($dy === 1) { // push right
            if ($a[$nx][$ny] === '[') {
                $this->push($a, $nx, $ny + 1, $dx, $dy);
                [$a[$nx][$ny + 1], $a[$nx][$ny], $a[$sx][$sy]] =
                    [$a[$nx][$ny], $a[$sx][$sy], $a[$nx][$ny + 1]];

                return;
            }
        }
    }

    private function solve(): void
    {
        $a = explode("\n", $this->data[0]);
        $b = str_replace("\n", '', $this->data[1]);
        $ans = 0;
        $n = count($a);
        $m = strlen($a[0]);

        $sx = $sy = 0;
        for ($i = 0; $i < $n; $i++) {
            $a[$i] = is_string($a[$i]) ? str_split($a[$i]) : $a[$i];
            for ($j = 0; $j < $m; $j++) {
                if ($a[$i][$j] === '@') {
                    $sx = $i;
                    $sy = $j;
                    $a[$i][$j] = '.';
                    break;
                }
            }
        }

        $dx = [0, -1, 0, 1];
        $dy = [-1, 0, 1, 0];

        foreach (str_split($b) as $mv) {
            $d = match ($mv) {
                '^' => 1,
                '<' => 0,
                '>' => 2,
                default => 3,
            };

            $k = 1;
            $flag = false;
            $blocked = false;

            while (true) {
                $nx = $sx + $dx[$d] * $k;
                $ny = $sy + $dy[$d] * $k;

                if ($a[$nx][$ny] === '#') {
                    $blocked = true;
                    break;
                } elseif ($a[$nx][$ny] === '.') {
                    $flag = true;
                    break;
                }
                $k++;
            }

            if (! $blocked) {
                [$a[$nx][$ny], $a[$sx + $dx[$d]][$sy + $dy[$d]]] =
                    [$a[$sx + $dx[$d]][$sy + $dy[$d]], $a[$nx][$ny]];
                $sx += $dx[$d];
                $sy += $dy[$d];
            }
        }

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                if ($a[$i][$j] === 'O') {
                    $ans += 100 * $i + $j;
                }
            }
        }

        echo $ans."\n";
    }

    public function part1(): int
    {
        ob_start();
        $this->solve();
        $result = (int) ob_get_clean();

        return $result;
    }

    public function part2(): int
    {
        ob_start();
        $this->solve2();
        $result = (int) ob_get_clean();

        return $result;
    }
}

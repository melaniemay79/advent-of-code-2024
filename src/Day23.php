<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day23
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $connected = [];

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

        $this->processInput(trim($input));
    }

    private function processInput(string $input): void
    {
        $lines = explode("\n", $input);
        $pairs = array_map(fn ($line) => explode('-', $line), $lines);

        foreach ($pairs as [$a, $b]) {
            $this->connected[$a][] = $b;
            $this->connected[$b][] = $a;
        }

        foreach ($this->connected as &$connections) {
            $connections = array_unique($connections);
        }
    }

    public function part1(): int
    {
        $sets = [];
        foreach ($this->connected as $first => $adjs) {
            foreach ($adjs as $second) {
                $commonNodes = array_intersect(
                    $this->connected[$second] ?? [],
                    $adjs
                );
                foreach ($commonNodes as $third) {
                    if ($third !== $first && $third !== $second) {
                        $triple = [$first, $second, $third];
                        sort($triple);
                        $sets[] = $triple;
                    }
                }
            }
        }

        $sets = array_map(
            fn (array $triple) => json_encode($triple, JSON_THROW_ON_ERROR),
            $sets
        );
        $sets = array_unique($sets);
        $sets = array_map(
            fn ($json) => json_decode($json, true, 512, JSON_THROW_ON_ERROR),
            $sets
        );

        $keep = array_filter($sets, function (mixed $set): bool {
            if (! is_array($set)) {
                return false;
            }

            return array_reduce($set, function (bool $carry, string $node): bool {
                return $carry || str_starts_with($node, 't');
            }, false);
        });

        return count($keep);
    }

    public function part2(): string
    {
        return $this->password([], array_keys($this->connected), []);
    }

    /**
     * Undocumented function
     *
     * @param  array<int, string>  $r
     * @param  array<int, string>  $p
     * @param  array<int, string>  $x
     */
    private function password(array $r, array $p, array $x): string
    {
        if (empty($p) && empty($x)) {
            return implode(',', $r);
        }

        $max = '';
        $pCopy = $p;
        // Sort the candidates to ensure we process them in lexicographical order
        sort($pCopy);

        foreach ($pCopy as $v) {
            $newR = array_merge($r, [$v]);
            $newP = array_intersect($p, $this->connected[$v] ?? []);
            $newX = array_intersect($x, $this->connected[$v] ?? []);

            $pw = $this->password($newR, $newP, $newX);
            if (strlen($pw) > strlen($max) || (strlen($pw) === strlen($max) && $pw < $max)) {
                $max = $pw;
            }

            $p = array_diff($p, [$v]);
            $x = array_merge($x, [$v]);
        }

        return $max;
    }
}

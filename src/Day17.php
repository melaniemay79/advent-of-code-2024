<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day17
{
    private int $registerA;

    private int $registerB;

    private int $registerC;

    /**
     * @var array<int|null>
     */
    private array $program;

    public function __construct(string $file)
    {
        if (! file_exists($file)) {
            throw new RuntimeException('File not found');
        }

        $input = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($input === false) {
            exit('Failed to read input file');
        }

        $this->registerA = (int) $this->getData($input[0]);
        $this->registerB = (int) $this->getData($input[1]);
        $this->registerC = (int) $this->getData($input[2]);
        $this->program = array_map('intval', explode(',', $this->getData($input[3])));
    }

    private function getData(string $line): string
    {
        return explode(': ', $line)[1];
    }

    private function combo(?int $a, ?int $b, ?int $c, ?int $value): ?int
    {
        return match ($value) {
            0, 1, 2, 3 => $value,
            4 => $a,
            5 => $b,
            6 => $c,
            default => null,
        };
    }

    /**
     * @param  array<int|null>  $program
     * @return array<int, int|null>
     */
    private function eval(?int $a, ?int $b, ?int $c, ?int $ip, array $program): array
    {
        $opcode = $program[$ip];
        $arg = $program[$ip + 1];
        $comb = $this->combo($a, $b, $c, $arg);

        switch ($opcode) {
            case 0:
                $num = $a;
                $denom = 2 ** $comb;

                return [null, (int) ($num / $denom), $b, $c, $ip + 2];
            case 1:
                return [null, $a, $b ^ $arg, $c, $ip + 2];
            case 2:
                return [null, $a, $comb % 8, $c, $ip + 2];
            case 3:
                return $a === 0 ? [null, $a, $b, $c, $ip + 2] : [null, $a, $b, $c, $arg];
            case 4:
                return [null, $a, $b ^ $c, $c, $ip + 2];
            case 5:
                return [$comb % 8, $a, $b, $c, $ip + 2];
            case 6:
                $num = $a;
                $denom = 2 ** $comb;

                return [null, $a, (int) ($num / $denom), $c, $ip + 2];
            case 7:
                $num = $a;
                $denom = 2 ** $comb;

                return [null, $a, $b, (int) ($num / $denom), $ip + 2];
            default:
                throw new RuntimeException('Invalid opcode: '.$opcode);
        }
    }

    /**
     * @param  array<int|null>  $program
     * @return array<int, int|null>
     */
    public function runProgram(int $a, int $b, int $c, array $program): array
    {
        $ip = 0;
        $res = [];
        while ($ip < count($program) - 1) {
            [$out, $a, $b, $c, $ip] = $this->eval($a, $b, $c, $ip, $program);
            if ($out !== null) {
                $res[] = $out;
            }
        }

        return $res;
    }

    /**
     * @param  array<int|null>  $program
     */
    public function getBestQuineInput(array $program, int $cursor, int $sofar): ?int
    {
        for ($candidate = 0; $candidate < 8; $candidate++) {
            if ($this->runProgram($sofar * 8 + $candidate, 0, 0, $program) === array_slice($program, $cursor)) {
                if ($cursor === 0) {
                    return $sofar * 8 + $candidate;
                }
                $ret = $this->getBestQuineInput($program, $cursor - 1, $sofar * 8 + $candidate);
                if ($ret !== null) {
                    return $ret;
                }
            }
        }

        return null;
    }

    /**
     * @return array<int, int|string|null>
     */
    public function execute(): array
    {
        $output = $this->runProgram($this->registerA, $this->registerB, $this->registerC, $this->program);

        return [
            implode(',', $output),
            $this->getBestQuineInput($this->program, count($this->program) - 1, 0),
        ];
    }
}

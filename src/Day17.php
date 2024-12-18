<?php

namespace AdventOfCode2024;

use RuntimeException;

class Day17
{
    private int $registerA;

    private int $registerB;

    private int $registerC;

    /**
     * @var array<int, int>
     */
    private array $program;

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
        preg_match('/Register A: (\d+)/', $input, $matchesA);
        preg_match('/Register B: (\d+)/', $input, $matchesB);
        preg_match('/Register C: (\d+)/', $input, $matchesC);

        $this->registerA = (int) ($matchesA[1] ?? 0);
        $this->registerB = (int) ($matchesB[1] ?? 0);
        $this->registerC = (int) ($matchesC[1] ?? 0);

        preg_match('/Program: (.+)/', $input, $matchesProgram);
        $this->program = array_map('intval', explode(',', $matchesProgram[1] ?? ''));
    }

    public function execute(): string
    {
        $output = [];
        $instructionPointer = 0;

        while ($instructionPointer < count($this->program)) {
            // Break if we're at the last instruction and can't read an operand
            if ($instructionPointer === count($this->program) - 1) {
                break;
            }

            $opcode = $this->program[$instructionPointer];
            $operand = $this->program[$instructionPointer + 1];

            switch ($opcode) {
                case 0: // adv
                    $this->registerA = (int) ($this->registerA / (2 ** $this->getComboValue($operand)));
                    $instructionPointer += 2;
                    break;
                case 1: // bxl
                    $this->registerB ^= $operand;
                    $instructionPointer += 2;
                    break;
                case 2: // bst
                    $this->registerB = $this->getComboValue($operand) % 8;
                    $instructionPointer += 2;
                    break;
                case 3: // jnz
                    if ($this->registerA !== 0) {
                        $instructionPointer = $operand;
                    } else {
                        $instructionPointer += 2;
                    }
                    break;
                case 4: // bxc
                    $this->registerB ^= $this->registerC;
                    $instructionPointer += 2;
                    break;
                case 5: // out
                    $output[] = $this->getComboValue($operand) % 8;
                    $instructionPointer += 2;
                    break;
                case 6: // bdv
                    $this->registerB = (int) ($this->registerA / (2 ** $this->getComboValue($operand)));
                    $instructionPointer += 2;
                    break;
                case 7: // cdv
                    $this->registerC = (int) ($this->registerA / (2 ** $this->getComboValue($operand)));
                    $instructionPointer += 2;
                    break;
            }
        }

        return implode(',', $output);
    }

    private function getComboValue(int $operand): int
    {
        if ($operand <= 3) {
            return $operand;
        }

        return match ($operand) {
            4 => $this->registerA,
            5 => $this->registerB,
            6 => $this->registerC,
            default => throw new RuntimeException('Invalid combo operand: '.$operand),
        };
    }

    public function findSelfReplicatingValue(): int
    {
        $targetProgram = implode(',', $this->program);
        $initialA = 1;

        while ($initialA <= PHP_INT_MAX) {
            $this->registerA = $initialA;
            $this->registerB = 0;
            $this->registerC = 0;

            $output = $this->execute();

            if ($output === $targetProgram) {
                return $initialA;
            }

            // Use larger increments initially, then refine when we get closer
            if ($initialA < 1000000000) {
                $initialA++;
            } elseif ($initialA < 10000000000) {
                $initialA += 10;
            } else {
                $initialA += 1000000000;
            }
        }
        throw new RuntimeException('No solution found within reasonable limits');
    }
}

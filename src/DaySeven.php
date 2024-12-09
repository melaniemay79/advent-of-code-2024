<?php

namespace AdventOfCode2024;

use RuntimeException;

class DaySeven
{
    private string $input;

    /**
     * @var array<array{target: int, numbers: array<int>}>
     */
    private array $equations = [];

    /**
     * @param  string  $file
     */
    public function __construct($file)
    {
        if (! file_exists($file)) {
            throw new RuntimeException('File not found');
        }

        $this->input = file_get_contents($file);
        $this->processInput();
    }

    private function processInput(): void
    {
        $lines = explode("\n", trim($this->input));
        foreach ($lines as $line) {
            if (preg_match('/^(\d+): (.*)$/', $line, $matches)) {
                $this->equations[] = [
                    'target' => (int) $matches[1],
                    'numbers' => array_map('intval', explode(' ', $matches[2])),
                ];
            }
        }
    }

    /**
     * @param  array<int>  $numbers
     * @param  array<string>  $operators
     */
    private function evaluate(array $numbers, array $operators, bool $includeConcatenation = false): int
    {
        $result = $numbers[0];
        for ($i = 0; $i < count($operators); $i++) {
            switch ($operators[$i]) {
                case '+':
                    $result += $numbers[$i + 1];
                    break;
                case '*':
                    $result *= $numbers[$i + 1];
                    break;
                case '.':
                    if ($includeConcatenation) {
                        $result = (int) ($result.$numbers[$i + 1]);
                    }
                    break;
            }
        }

        return $result;
    }

    /**
     * @param  array{target: int, numbers: array<int>}  $equation
     */
    private function isTarget(array $equation, bool $includeConcatenation = false): bool
    {
        $numbers = $equation['numbers'];
        $target = $equation['target'];
        $operatorCount = count($numbers) - 1;

        $combinations = pow(3, $operatorCount);
        for ($i = 0; $i < $combinations; $i++) {
            $operators = [];
            $temp = $i;
            for ($j = 0; $j < $operatorCount; $j++) {
                // Use modulo to get each operator
                switch ($temp % 3) {
                    case 0:
                        $operators[] = '*';
                        break;
                    case 1:
                        $operators[] = '+';
                        break;
                    case 2:
                        if ($includeConcatenation) {
                            $operators[] = '.';
                        }
                        break;
                }
                $temp = (int) ($temp / 3);
            }

            if ($this->evaluate($numbers, $operators, $includeConcatenation) === $target) {
                return true;
            }
        }

        return false;
    }

    public function getResult(bool $includeConcatenation = false): int
    {
        $sum = 0;
        foreach ($this->equations as $equation) {
            if ($this->isTarget($equation, $includeConcatenation)) {
                $sum += $equation['target'];
            }
        }

        return $sum;
    }
}

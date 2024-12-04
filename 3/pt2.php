<?php
$input = file_get_contents('input.txt');

if ($input === false) exit('Failed to read input file');

preg_match_all('/(?:mul|do|dont)\(\d{1,3},\d+\)/', $input, $matches);

$sum = 0;
$shouldMultiply = true;

preg_match_all('/(?:mul\(\d+,\d+\)|do\(\)|don\'t\(\))/', $input, $matches);

foreach ($matches[0] as $operation) {
    if ($operation === 'do()') {
        $shouldMultiply = true;
        continue;
    }
    
    if ($operation === "don't()") {
        $shouldMultiply = false;
        continue;
    }
    
    if (preg_match('/mul\((\d+),(\d+)\)/', $operation, $numbers)) {
        if ($shouldMultiply) {
            $sum += intval($numbers[1]) * intval($numbers[2]);
        }
    }
}

echo $sum; // 77055967
?>
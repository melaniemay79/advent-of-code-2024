<?php
$input = file_get_contents('input.txt');

if ($input === false) exit('Failed to read input file');

preg_match_all('/mul\(\d{1,3},\d+\)/', $input, $matches);

$sum = 0;

preg_match_all('/mul\(\d{1,3},\d+\)/', $input, $matches);

foreach ($matches[0] as $match) {
    preg_match('/mul\((\d+),(\d+)\)/', $match, $numbers);
    if (isset($numbers[1]) && isset($numbers[2])) {
        $sum += intval($numbers[1]) * intval($numbers[2]);
    }
}

echo $sum; // 153469856
?>
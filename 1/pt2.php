<?php
$lines = file('input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($lines === false) exit('Failed to read input file');

$set1 = [];
$set2 = [];

foreach ($lines as $line) {
    list($v1, $v2) = preg_split('/\s+/', trim($line));

    $set1[] = trim($v1);
    $set2[] = trim($v2);
}

$set1 = array_map('trim', $set1);
$set2 = array_map('trim', $set2);
$sum = 0;

foreach ($set1 as $value) {
    $occurrences = array_count_values($set2)[$value] ?? 0;
    $sum += intval($value) * $occurrences;
}

echo $sum; //24643097
?>

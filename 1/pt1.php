<?php
$lines = file('input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($lines === false) exit('Failed to read input file');

$set1 = [];
$set2 = [];

foreach ($lines as $line) {
    $parts = preg_split('/\s+/', trim($line));
    if ($parts !== false && count($parts) >= 2) {
        list($v1, $v2) = $parts;
        $set1[] = trim($v1);
        $set2[] = trim($v2);
    }
}

$set1 = array_map('trim', $set1);
$set2 = array_map('trim', $set2);

$sum = array_sum(array_map(function($v1, $v2) {
    return abs(intval($v1) - intval($v2));
}, $set1, $set2));

echo $sum; //29709735
?>

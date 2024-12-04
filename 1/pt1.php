<?php
$lines = file('input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$set1 = [];
$set2 = [];

foreach ($lines as $line) {
    list($v1, $v2) = preg_split('/\s+/', trim($line));

    $set1[] = trim($v1);
    $set2[] = trim($v2);
}

$set1 = array_map('trim', $set1);
$set2 = array_map('trim', $set2);

$sum = array_sum(array_map(function($v1, $v2) {
    return abs($v1 - $v2);
}, $set1, $set2));

echo $sum;
?>

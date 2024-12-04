<?php
$lines = file('input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($lines === false) exit('Failed to read input file');

$report = [];

foreach ($lines as $line) {
    if (empty(trim($line))) continue;
    $v = explode(' ', trim($line));
    $v = array_map('intval', $v);
    $report[] = $v;
}

$safe = 0;
foreach ($report as $k=>$v) {
  if (! isUnsafe($v)) {
      $safe++;
  }
}

echo $safe; // 717

/**
 * @param int[] $numbers
 */
function isUnsafe(array $numbers): bool
{   
    if (!isUnsafeSequence($numbers)) {
        return false;
    }
    
    for ($i = 0; $i < count($numbers); $i++) {
        $tempArray = array_values(array_filter($numbers, function($key) use ($i) {
            return $key !== $i;
        }, ARRAY_FILTER_USE_KEY));
        
        if (!isUnsafeSequence($tempArray)) {
            return false;
        }
    }
    
    return true;
}

/**
 * @param int[] $numbers
 */
function isUnsafeSequence(array $numbers): bool
{
    if (count($numbers) < 2) return false;
    
    $isIncreasing = $numbers[1] > $numbers[0];
    
    for ($i = 0; $i < count($numbers) - 1; $i++) {
        $diff = $numbers[$i + 1] - $numbers[$i];
        
        if ($isIncreasing && $diff <= 0) {
            return true;
        }
        if (!$isIncreasing && $diff >= 0) {
            return true;
        }
        
        if (abs($diff) > 3 || $diff == 0) {
            return true;
        }
    }
    
    return false;
}
?>
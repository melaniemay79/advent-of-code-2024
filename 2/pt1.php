<?php
$lines = file('input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

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

print $safe;

function isUnsafe($numbers) {   
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
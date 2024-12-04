<?php
$input = file_get_contents('input.txt');

if ($input === false) exit('Failed to read input file');

$input = trim($input);

$lines = [];
$lineLength = strpos($input, "\n") ?: strlen($input);
for ($i = 0; $i < strlen($input); $i += $lineLength + 1) {
    $line = substr($input, $i, $lineLength);
    if ($line) {
        $lines[] = $line;
    }
}

$total = find($lines);
echo $total; // 2536

/**
 * @param string[] $lines
 */
function find(array $lines): int
{
    $count = 0;
    $rows = count($lines);
    $cols = strlen($lines[0]);

    for ($row = 0; $row < $rows - 2; $row++) {
        for ($col = 0; $col < $cols - 2; $col++) {
            if (check($lines, $row, $col)) {
                $count++;
            }
        }
    }

    return $count;
}

/**
 * @param string[] $lines
 */
function check(array $lines, int $row, int $col): bool
{
    $pos1a = $lines[$row][$col];
    $pos2a = $lines[$row][$col + 2];

    $mid = $lines[$row + 1][$col + 1]; // must be a

    $pos1b = $lines[$row + 2][$col + 2];
    $pos2b = $lines[$row + 2][$col];

    if ($mid !== 'A') return false;

    if (
        (($pos1a == 'M' && $pos1b == 'S') || ($pos1a == 'S' && $pos1b == 'M')) &&
        (($pos2a == 'M' && $pos2b == 'S') || ($pos2a == 'S' && $pos2b == 'M'))
    ) {
        echo $lines[$row][$col] . "-" . $lines[$row][$col + 2] . "\n";
        echo "-" . $lines[$row + 1][$col + 1] . "-" . "\n";
        echo $lines[$row + 2][$col] . "-" . $lines[$row + 2][$col + 2] . "\n";
        return true;
    }

    return false;
}
?>
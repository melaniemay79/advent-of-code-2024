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

    $directions = [
        [0, 1],   // right
        [0, -1],  // left
        [1, 0],   // down
        [-1, 0],  // up
        [1, 1],   // diagonal down-right
        [-1, -1], // diagonal up-left
        [1, -1],  // diagonal down-left
        [-1, 1]   // diagonal up-right
    ];

    foreach ($lines as $row => $line) {
        for ($col = 0; $col < $cols; $col++) {
            foreach ($directions as $direction) {
                if (check($lines, 'XMAS', $row, $col, $direction[0], $direction[1])) {
                    $count++;
                }
            }
        }
    }

    return $count;
}

/**
 * @param string[] $lines
 */
function check(array $lines, string $word, int $startRow, int $startCol, int $dirRow, int $dirCol): bool
{
    $len = strlen($word);
    for ($i = 0; $i < $len; $i++) {
        $row = $startRow + $i * $dirRow;
        $col = $startCol + $i * $dirCol;
        if ($row < 0 || $row >= count($lines) || $col < 0 || $col >= strlen($lines[$row]) || $lines[$row][$col] !== $word[$i]) {
            return false;
        }
    }
    return true;
}
?>
$input = file_get_contents('input.txt');

preg_match_all('/mul\(\d{1,3},\d+\)/', $input, $matches);

$sum = 0;

preg_match_all('/mul\(\d{1,3},\d+\)/', $input, $matches);

foreach ($matches[0] as $match) {
    preg_match('/mul\((\d+),(\d+)\)/', $match, $numbers);
    $sum += intval($numbers[1]) * intval($numbers[2]);
}

echo $sum;
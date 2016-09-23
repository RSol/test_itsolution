<?php

function fillArray($size)
{
    $i = 0;
    $arr = [];
    while ($i < $size) {
        $val = rand(1, 2 << 32);
        while (in_array($val, $arr)) {
//            echo "$i - $val\n";
            $val = rand(1, 2 << 32);
        }
        $arr[] = $val;
        $i++;
    }

    return $arr;
}

function fillBigArray($size)
{
    $chunk = 100000;
    if ($size < $chunk) {
        return fillArray($size);
    }

    $i = $chunk;
    $res = [];
    while ($i < $size) {
//        echo "$i\n";
        $len = ($i + $chunk) < $size ? $chunk : ($size - $i);
        $res = array_unique(array_merge($res, fillArray($len)));
        $i = count($res);
    }

    return $res;
}

$len = 1000000;
$arr = fillBigArray($len);

$change = rand(0, $len);
$changed = rand(0, $len);
while ($changed == $change) {
    $changed = rand(0, $len);
}

$arr[$changed] = $arr[$change];

echo "$change <=> $changed = {$arr[$change]}\n";

function quicksort(&$array, $l = 0, $r = 0)
{
    if ($r === 0) $r = count($array) - 1;
    $i = $l;
    $j = $r;
    $x = $array[($l + $r) / 2];
    do {
        while ($array[$i] < $x) $i++;
        while ($array[$j] > $x) $j--;
        if ($i <= $j) {
            if ($array[$i] > $array[$j])
                list($array[$i], $array[$j]) = [$array[$j], $array[$i]];
            $i++;
            $j--;
        }
    } while ($i <= $j);
    if ($i < $r) quicksort($array, $i, $r);
    if ($j > $l) quicksort($array, $l, $j);
}

// -------------1-------------
function find1($arr) {
    quicksort($arr);
    foreach ($arr as $i => $item) {
        if ($arr[$i] == $arr[$i + 1]) {
            echo "1: $i - {$arr[$i]} == {$arr[$i + 1]}\n";
            return $arr[$i];
        }
    }
}

// -------------2-------------
function find2($arr) {
    sort($arr);
    foreach ($arr as $i => $item) {
        if ($arr[$i] == $arr[$i + 1]) {
            echo "2: $i - {$arr[$i]} == {$arr[$i + 1]}\n";
            return $arr[$i];
        }
    }
}

// -------------3-------------
function find3($arr) {
    $len = count($arr);
    for ($i = 0; $i < $len - 1; $i++) {
        for ($j = $i + 1; $j < $len; $j++) {
            if ($arr[$i] == $arr[$j]) {
                echo "3: $i - {$arr[$i]} == {$arr[$i + 1]}\n";
                return $arr[$i];
            }
        }
    }
}
$k1 = find1($arr);
echo "$k1\n";
$k2 = find2($arr);
echo "$k2\n";
$k3 = find3($arr);
echo "$k3\n";

//quicksort($arr);
//
//var_dump($arr);
<?php

namespace Differ\Differ;

use function Differ\Parser\parse;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    try {
        $array1 = parse($pathToFile1);
        $array2 = parse($pathToFile2);    
    } catch (\Exception $e) {
        echo $e->getMessage();
        exit();
    }
    $diff = findDiff($array1, $array2);

    return diffToString($diff);
}

function findDiff(array $array1, array $array2): array
{
    $result = [];
    foreach ($array1 as $k => $v) {
        if (array_key_exists($k, $array2)) {
            if ($v === $array2[$k]) {
                $result[$k]["  {$k}"] = $v;
            } else {
                $result[$k]["- {$k}"] = $v;
                $result[$k]["+ {$k}"] = $array2[$k];
            }
        } else {
            $result[$k]["- {$k}"] = $v;
        }
    }
    $diff = array_diff($array2, $array1);

    foreach ($diff as $k => $v) {
        if (!array_key_exists($k, $array1)) {
            $result[$k]["+ {$k}"] = $v;
        }
    }

    ksort($result);

    return flatten($result);
}

function diffToString(array $array): string
{
    $result = "{\n";
    foreach ($array as $k => $v) {
        if (is_bool($v)) {
            $v = $v ? 'true' : 'false';
        }
        $result .= "  {$k}: {$v}\n";
    }
    $result .= "}\n";

    return $result;
}

function flatten(array $array): array
{
    $result = [];
    array_walk_recursive($array, function ($v, $k) use (&$result) {
        $result[$k] = $v;
    });

    return $result;
}

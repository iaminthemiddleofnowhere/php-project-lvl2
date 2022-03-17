<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\Formatters\format;
use function Functional\sort;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish')
{
    $array1 = parse($pathToFile1);
    $array2 = parse($pathToFile2);
    if (!$array1 || !$array2) {
        echo "Something wrong with file's paths";
        return;
    }
    $diff = findDiff($array1, $array2);

    return format($diff, $format);
}

function findDiff(array $array1, array $array2)
{
    $iter = function ($currentArray1, $currentArray2) use (&$iter) {
        $diff1 = array_map(function ($key1, $value1) use (&$currentArray2, &$iter) {
            if (array_key_exists($key1, $currentArray2)) {
                $value2 = $currentArray2[$key1];
                if (!is_array($value1) && !is_array($value2)) {
                    if ($value1 === $value2) {
                        return ["key" => $key1, "status" => "unchanged", "value" => $value1];
                    } else {
                        return ["key" => $key1, "status" => "changed", "removed" => $value1, "added" => $value2];
                    }
                } elseif (is_array($value1) && is_array($value2)) {
                    return ["key" => $key1, "status" => "unchanged", "children" => $iter($value1, $value2)];
                } else {
                    return ["key" => $key1, "status" => "changed", "removed" => $value1, "added" => $value2];
                }
            } else {
                return ["key" => $key1, "status" => "changed", "removed" => $value1];
            }
        },
        array_keys($currentArray1),
        $currentArray1);

        $diff2 = array_map(function ($key2, $value2) use ($currentArray1) {
            if (!array_key_exists($key2, $currentArray1)) {
                return ["key" => $key2, "status" => "changed", "added" => $value2];
            }
        },
        array_keys($currentArray2),
        $currentArray2);

        $result = array_merge($diff1, array_filter($diff2));

        return sort($result, fn($a, $b) => strcmp($a['key'], $b['key']));
    };

    return $iter($array1, $array2);
}

<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\Formatters\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    try {
        $array1 = parse($pathToFile1);
        $array2 = parse($pathToFile2);
    } catch (\Exception $e) {
        echo $e->getMessage();
        exit();
    }
    $diff = findDiff($array1, $array2);

    return format($diff, $format);
}

function findDiff(array $array1, array $array2): array
{
    $iter = function ($currentArray1, $currentArray2) use (&$iter) {
        $result = array_map(function ($key1, $value1) use (&$currentArray2, &$iter) {
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

        foreach ($currentArray2 as $key2 => $value2) {
            if (!array_key_exists($key2, $currentArray1)) {
                $result[] = ["key" => $key2, "status" => "changed", "added" => $value2];
            }
        }

        usort($result, fn($a, $b) => $a["key"] > $b["key"]);

        return $result;
    };

    return $iter($array1, $array2);
}

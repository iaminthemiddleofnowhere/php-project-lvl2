<?php

namespace Differ\Differ;

function genDiff(string $pathToFile1, string $pathToFile2)
{
    $array1 = arrayBoolsToStr(json_decode(file_get_contents($pathToFile1), true));
    $array2 = arrayBoolsToStr(json_decode(file_get_contents($pathToFile2), true));
    $result = "{\n";

    foreach ($array1 as $k => $v) {
        if (array_key_exists($k, $array2)) {
            if ($v === $array2[$k]) {
                $result .= "\t{$k}: {$v}\n";
            } else {
                $result .= "\t- {$k}: {$v}\n";
                $result .= "\t+ {$k}: {$array2[$k]}\n";
            }
        } else {
            $result .= "\t- {$k}: {$v}\n";
        }
    }
    $diff = array_diff($array2, $array1);

    foreach ($diff as $k => $v) {
        if (!array_key_exists($k, $array1)) {
            $result .= "\t+ {$k}: {$v}\n";
        }
    }
    $result .= "}\n";

    return $result;
}

function arrayBoolsToStr(array $array)
{
    return array_map(function ($v) {
        if (is_bool($v)) {
            return var_export($v, true);
        }
        return $v;
    }, $array);
}

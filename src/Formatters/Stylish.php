<?php

namespace Differ\Formatters\Stylish;

function diffToString(array $array, string $replacer = " ", int $spaceCount = 2)
{
    $iter = function ($currentValue, $depth, $isDiff = true) use (&$iter, $replacer, $spaceCount) {
        if (!is_array($currentValue)) {
            if (is_null($currentValue)) {
                return 'null';
            }
            return trim(var_export($currentValue, true), "'");
        }

        $currentIndent = str_repeat($replacer, $spaceCount * $depth);
        $currentBracketIndent = str_repeat($replacer, $spaceCount * $depth - $spaceCount);

        $lines = array_map(function ($key, $value) use (&$iter, $isDiff, $currentIndent, $depth) {
            if ($isDiff) {
                if (array_key_exists("children", $value)) {
                    return "{$currentIndent}  {$value['key']}: {$iter($value['children'], $depth + 2)}";
                }
                if ($value['status'] === "changed") {
                    $result = [];
                    if (array_key_exists("removed", $value)) {
                        $result[] = "{$currentIndent}- {$value['key']}: {$iter($value['removed'], $depth + 2, false)}";
                    }
                    if (array_key_exists("added", $value)) {
                        $result[] = "{$currentIndent}+ {$value['key']}: {$iter($value['added'], $depth + 2, false)}";
                    }
                    return implode("\n", $result);
                } elseif ($value['status'] === "unchanged") {
                    return  "{$currentIndent}  {$value['key']}: {$iter($value['value'], $depth + 2, false)}";
                }
            } else {
                return "{$currentIndent}  {$key}: {$iter($value, $depth + 2, false)}";
            }
        },
        array_keys($currentValue),
        $currentValue);

        $result = ["{", ...$lines, "{$currentBracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($array, 1);
}

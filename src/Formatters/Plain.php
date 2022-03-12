<?php

namespace Differ\Formatters\Plain;

function diffToString(array $array): string
{
    $iter = function ($currentValue, $currentPath) use (&$iter) {
        $lines = array_reduce($currentValue, function ($carry, $value) use (&$iter, $currentPath) {
            $currentPath[] = $value['key'];
            $str_path = implode(".", $currentPath);
            if (array_key_exists("children", $value)) {
                $carry[] = $iter($value['children'], $currentPath);
            }
            if ($value['status'] === "changed") {
                if (array_key_exists("removed", $value) && array_key_exists("added", $value)) {
                    $added = valueToStr($value['added']);
                    $removed = valueToStr($value['removed']);
                    $carry[] = "Property '{$str_path}' was updated. From {$removed} to {$added}";
                } elseif (array_key_exists("added", $value)) {
                    $added = valueToStr($value['added']);
                    $carry[] = "Property '{$str_path}' was added with value: {$added}";
                } elseif (array_key_exists("removed", $value)) {
                    $carry[] = "Property '{$str_path}' was removed";
                }
            }

            return $carry;
        });

        return implode("\n", $lines);
    };

    return $iter($array, []);
}

function valueToStr($value)
{
    if (!is_array($value)) {
        if (is_null($value)) {
            return 'null';
        }
        return var_export($value, true);
    } else {
        return "[complex value]";
    }
}

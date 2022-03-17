<?php

namespace Differ\Formatters\Plain;

function diffToString(array $array)
{
    $iter = function ($currentValue, $pathToValue) use (&$iter) {
        $lines = array_map(function ($value) use (&$iter, $pathToValue) {
            $currentPath = trim("{$pathToValue}.{$value['key']}", '.');
            if (array_key_exists("children", $value)) {
                return $iter($value['children'], $currentPath);
            }
            if ($value['status'] === "changed") {
                if (array_key_exists("removed", $value) && array_key_exists("added", $value)) {
                    $added = valueToStr($value['added']);
                    $removed = valueToStr($value['removed']);
                    return "Property '{$currentPath}' was updated. From {$removed} to {$added}";
                } elseif (array_key_exists("added", $value)) {
                    $added = valueToStr($value['added']);
                    return "Property '{$currentPath}' was added with value: {$added}";
                } elseif (array_key_exists("removed", $value)) {
                    return "Property '{$currentPath}' was removed";
                }
            }
        }, $currentValue);

        return implode("\n", array_filter($lines));
    };

    return $iter($array, null);
}

function valueToStr(mixed $value)
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

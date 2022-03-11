<?php
function toString($value)
{
     return trim(var_export($value, true), "'");
}

function stringify($value, $replacer = " ", $spacesCount = 1)
{
    $iter = function($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }
        $identSize = $spacesCount * $depth;
        $currentIdent = str_repeat($replacer, $identSize);
        $bracketIdent = str_repeat($replacer, $identSize - $spacesCount);
        $lines = array_map(
            fn($k, $v) => "{$currentIdent}{$k}: {$iter($v, $depth + 1)}",
            array_keys($currentValue), 
            $currentValue
        );
        $result = ["{", ...$lines, "{$bracketIdent}}"];
        return implode("\n", $result);
    };
    return $iter($value, 1);
}

$nested = [
    'string' => 'value',
    'boolean' => true,
    'number' => 5,
    'float' => 1.25,
    'object' => [
        5 => 'number',
        '1.25' => 'float',
        'null' => [
            'puzda' => 123
        ],
        'true' => 'boolean',
        'value' => 'string',
        'nested' => [
            'boolean' => true,
            'float' => 1.25,
            'string' => 'value',
            'number' => 5,
            'null' => [
                'hui' => 123
            ]
        ]
    ]
];
echo stringify($nested, '|-', 2);
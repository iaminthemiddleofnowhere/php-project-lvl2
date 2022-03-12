<?php

namespace Differ\Formatters\Formatters;

use function Differ\Formatters\Stylish\diffToString as stylish;
use function Differ\Formatters\Plain\diffToString as plain;

function format(array $diff, string $format): string
{
    switch ($format) {
        case 'plain':
            return plain($diff);
        case 'stylish':
        default:
            return stylish($diff);
    }
}

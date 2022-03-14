<?php

namespace Differ\Formatters\Formatters;

use function Differ\Formatters\Stylish\diffToString as stylish;
use function Differ\Formatters\Plain\diffToString as plain;
use function Differ\Formatters\Json\diffToString as json;

function format(array $diff, string $format)
{
    switch ($format) {
        case 'plain':
            return plain($diff);
        case 'json':
            return json($diff);
        case 'stylish':
        default:
            return stylish($diff);
    }
}

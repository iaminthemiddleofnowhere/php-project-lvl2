<?php

namespace Differ\Formatters\Json;

function diffToString(array $array)
{
    return json_encode($array, JSON_PRETTY_PRINT);
}

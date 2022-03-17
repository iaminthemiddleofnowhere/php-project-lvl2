<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $path)
{
    $realPath = realpath($path);
    $ext = pathinfo($realPath, PATHINFO_EXTENSION);
    $data = file_get_contents($path);

    switch ($ext) {
        case 'json':
            return json_decode($data, true);
        case 'yaml':
        case 'yml':
            return Yaml::parse($data);
    }
}

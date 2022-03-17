<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $path)
{
    $realPath = realpath($path);
    if ($realPath === false) {
        return false;
    }
    $ext = pathinfo($realPath, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    if ($data === false) {
        return false;
    }

    switch ($ext) {
        case 'json':
            return json_decode($data, true);
        case 'yaml':
        case 'yml':
            return Yaml::parse($data);
    }
}

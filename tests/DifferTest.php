<?php

namespace Differ\Phpunit\TestDiffer;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\findDiff;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiffJSON(): void
    {
        $pathToFile1 = __DIR__ . "/fixtures/file1.json";
        $pathToFile2 = __DIR__ . "/fixtures/file2.json";
        $expected = file_get_contents(__DIR__ . "/fixtures/result");
        $actual = genDiff($pathToFile1, $pathToFile2);
        $this->assertSame($expected, $actual);
    }

    public function testGenDiffYaml(): void
    {
        $pathToFile1 = __DIR__ . "/fixtures/file1.yml";
        $pathToFile2 = __DIR__ . "/fixtures/file2.yml";
        $expected = file_get_contents(__DIR__ . "/fixtures/result");
        $actual = genDiff($pathToFile1, $pathToFile2);
        $this->assertSame($expected, $actual);

    }
}

<?php

namespace Differ\Phpunit\TestDiffer;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\findDiff;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testFindDiff(): void
    {
        $array1 = [
            "w" => 2,
            "d" => 4,
            "a" => 5,
            "p" => 6,
        ];
        $array2 = [
            "a" => 3,
            "w" => 4,
            "c" => 3,
            "p" => 6
        ];
        $expected = [
            "- a" => 5,
            "+ a" => 3,
            "+ c" => 3,
            "- d" => 4,
            "  p" => 6,
            "- w" => 2,
            "+ w" => 4
        ];
        $actual = findDiff($array1, $array2);
        $this->assertSame($expected, $actual);
    } 

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

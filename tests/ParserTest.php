<?php

namespace Tests;

use Exprml\Decoder;
use Exprml\Evaluator;
use Exprml\Evaluator\Config;
use Exprml\Parser;
use Exprml\PB\Exprml\V1\DecodeInput;
use Exprml\PB\Exprml\V1\DefStack;
use Exprml\PB\Exprml\V1\EvaluateInput;
use Exprml\PB\Exprml\V1\EvaluateOutput;
use Exprml\PB\Exprml\V1\ParseInput;
use Exprml\PB\Exprml\V1\Value;
use Exprml\PB\Exprml\V1\Value\Type;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public static function provideTestParse(): array
    {
        $testcases = [];
        $dataDir = join(DIRECTORY_SEPARATOR, [__DIR__, "..", "testdata", "parser", "error"]);
        $filePaths = [$dataDir];
        while (count($filePaths) > 0) {
            $path = array_shift($filePaths);
            if ($path == "." || $path == "..") {
                continue;
            }
            if (is_dir($path)) {
                foreach (scandir($path) as $child) {
                    if ($child == "." || $child == "..") {
                        continue;
                    }
                    $filePaths[] = join(DIRECTORY_SEPARATOR, [$path, $child]);
                }
                continue;
            }
            if (str_ends_with($path, ".in.yaml")) {
                $key = mb_strimwidth($path, 0, strlen($path) - strlen(".in.yaml"));
                if (!array_key_exists($key, $testcases)) {
                    $testcases[$key] = new ParserTestcase();
                }
                $testcases[$key]->inputYaml = file_get_contents($path);
            }
        }
        $data = [];
        foreach ($testcases as $name => $testcase) {
            $data[$name] = [$testcase];
        }
        return $data;
    }

    #[DataProvider('provideTestParse')]
    public function testParse_Error(ParserTestcase $testcase)
    {
        $decodeResult = (new Decoder())->decode((new DecodeInput())->setYaml($testcase->inputYaml));
        $this->assertFalse($decodeResult->getIsError());

        $parseResult = (new Parser())->parse((new ParseInput())->setValue($decodeResult->getValue()));
        $this->assertTrue($parseResult->getIsError());
    }
}
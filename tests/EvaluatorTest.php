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
use PHPUnit\Framework\TestCase;

class EvaluatorTest extends TestCase
{
    /** @var array */
    private $testcases = [];

    protected function setUp(): void
    {
        $dataDir = join(DIRECTORY_SEPARATOR, [__DIR__, "..", "testdata", "evaluator"]);
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
                if (!array_key_exists($key, $this->testcases)) {
                    $this->testcases[$key] = new EvaluatorTestcase;
                }
                $this->testcases[$key]->inputYaml = file_get_contents($path);
            } elseif (str_ends_with($path, ".want.yaml")) {
                $key = mb_strimwidth($path, 0, strlen($path) - strlen(".want.yaml"));
                if (!array_key_exists($key, $this->testcases)) {
                    $this->testcases[$key] = new EvaluatorTestcase;
                }
                $yaml = (new Decoder())->decode((new DecodeInput())->setYaml(file_get_contents($path)));
                if ($yaml->getIsError()) {
                    $this->fail($yaml->getErrorMessage());
                }
                /** @var Value $wantValue */
                $wantValue = $yaml->getValue()->getObj()["want_value"];
                if ($wantValue != null) {
                    $this->testcases[$key]->wantValue = $wantValue;
                }
                /** @var Value $wantError */
                $wantError = $yaml->getValue()->getObj()["want_error"];
                if ($wantError != null) {
                    $this->testcases[$key]->wantError = $wantError->getBool();
                }
            }
        }
    }

    public function testEvaluate()
    {
        $names = array_keys($this->testcases);
        foreach ($names as $name) {
            print_r($name . "\n");
            /** @var EvaluatorTestcase $t */
            $t = $this->testcases[$name];

            $decodeResult = (new Decoder())->decode((new DecodeInput())->setYaml($t->inputYaml));
            $this->assertFalse($decodeResult->getIsError());

            $parseResult = (new Parser())->parse((new ParseInput())->setValue($decodeResult->getValue()));
            $this->assertFalse($parseResult->getIsError());

            $sut = new Evaluator(new Config());
            $got = $sut->evaluateExpr((new EvaluateInput())->setExpr($parseResult->getExpr())->setDefStack(new DefStack()));

            if ($t->wantError) {
                $this->assertNotEquals(EvaluateOutput\Status::OK, $got->getStatus());
            } else {
                $this->assertEquals(EvaluateOutput\Status::OK, $got->getStatus());
                $msg = $this->checkValueEqual([], $t->wantValue, $got->getValue());
                if ($msg != null) {
                    $this->fail($msg);
                }
            }

        }
    }

    /**
     * @param string[] $path
     * @param Value $want
     * @param Value $got
     * @return string|null
     */
    private function checkValueEqual(array $path, Value $want, Value $got): string|null
    {
        if ($want->getType() != $got->getType()) {
            return sprintf("type mismatch: /%s", join("/", $path));
        }
        switch ($want->getType()) {
            case Type::NULL:
                return null;
            case Type::BOOL:
                if ($want->getBool() != $got->getBool()) {
                    return sprintf("boolean mismatch want %s, got %s: /%s", $want->getBool(), $got->getBool(), join("/", $path));
                }
                return null;
            case Type::NUM:
                if ($want->getNum() != $got->getNum()) {
                    return sprintf("number mismatch want %s, got %s: /%s", $want->getNum(), $got->getNum(), join("/", $path));
                }
                return null;
            case Type::STR:
                if ($want->getStr() != $got->getStr()) {
                    return sprintf("string mismatch want %s, got %s: /%s", $want->getStr(), $got->getStr(), join("/", $path));
                }
                return null;
            case Type::ARR:
                if ($want->getArr()->count() != $got->getArr()->count()) {
                    return sprintf("array length mismatch want %d, got %d: /%s", $want->getArr()->count(), $got->getArr()->count(), join("/", $path));
                }
                foreach ($want->getArr() as $index => $wantItem) {
                    $gotItem = $got->getArr()[$index];
                    $msg = $this->checkValueEqual([...$path, (string)$index], $wantItem, $gotItem);
                    if ($msg != null) {
                        return $msg;
                    }
                }
                return null;
            case Type::OBJ:
                $wk = array_keys(iterator_to_array($want->getObj()));
                $gk = array_keys(iterator_to_array($got->getObj()));
                sort($wk);
                sort($gk);
                if ($wk != $gk) {
                    return sprintf("object keys mismatch want [%s], got [%s]: /%s", join(",", $wk), join(",", $gk), join("/", $path));
                }
                foreach ($want->getObj() as $key => $wantItem) {
                    /** @var Value $gotItem */
                    $gotItem = $got->getObj()[$key];
                    $msg = $this->checkValueEqual([...$path, $key], $wantItem, $gotItem);
                    if ($msg != null) {
                        return $msg;
                    }
                }
                return null;
            default:
                assert(false, sprintf("unexpected type: %s", Type::name($want->getType())));
        }
    }
}
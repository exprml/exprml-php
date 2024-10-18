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
use Exprml\PB\Exprml\V1\Expr\Path;
use Exprml\PB\Exprml\V1\ParseInput;
use Exprml\PB\Exprml\V1\Value;
use Exprml\PB\Exprml\V1\Value\Type;
use PHPUnit\Framework\TestCase;

class EvaluatorTest extends TestCase
{
    public static function provideTestEvaluate(): array
    {
        $testcases = [];
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
            if (preg_match("/^.*\.in\.yaml$/", $path)) {
                $key = mb_strimwidth($path, 0, strlen($path) - strlen(".in.yaml"));
                if (!array_key_exists($key, $testcases)) {
                    $testcases[$key] = new EvaluatorTestcase;
                }
                $testcases[$key]->inputYaml = file_get_contents($path);
            } elseif (preg_match("/^.*\.want\.yaml$/", $path)) {
                $key = mb_strimwidth($path, 0, strlen($path) - strlen(".want.yaml"));
                if (!array_key_exists($key, $testcases)) {
                    $testcases[$key] = new EvaluatorTestcase;
                }
                $yaml = (new Decoder())->decode((new DecodeInput())->setYaml(file_get_contents($path)));
                assert(!$yaml->getIsError(), $yaml->getErrorMessage());

                if ($yaml->getValue()->getObj()->offsetExists("want_value")) {
                    /** @var Value $wantValue */
                    $wantValue = $yaml->getValue()->getObj()["want_value"];
                    $testcases[$key]->wantValue = $wantValue;
                } else {
                    $testcases[$key]->wantValue = null;
                }
                if ($yaml->getValue()->getObj()->offsetExists("want_error")) {
                    /** @var Value $wantError */
                    $wantError = $yaml->getValue()->getObj()["want_error"];
                    $testcases[$key]->wantError = $wantError->getBool();
                } else {
                    $testcases[$key]->wantError = null;
                }
            }
        }
        $data = [];
        foreach ($testcases as $name => $testcase) {
            $data[$name] = [$testcase];
        }
        return $data;
    }

    public static function provideTestEvaluate_Extension(): array
    {
        return [
            "Ref" => [
                new EvaluatorExtensionTestcase(
                    '$test_func',
                    (new Value())->setType(Type::OBJ)->setObj([]),
                )],
            "Call" => [
                new EvaluatorExtensionTestcase(
                    '$test_func: { $arg: "`value`" }',
                    (new Value())->setType(Type::OBJ)->setObj([
                        '$arg' => (new Value())->setType(Type::STR)->setStr("value"),
                    ]),
                )],
        ];
    }


    /**
     * @dataProvider provideTestEvaluate
     * @param EvaluatorTestcase $testcase
     * @return void
     */
    public function testEvaluate(EvaluatorTestcase $testcase)
    {
        $decodeResult = (new Decoder())->decode((new DecodeInput())->setYaml($testcase->inputYaml));
        $this->assertFalse($decodeResult->getIsError());

        $parseResult = (new Parser())->parse((new ParseInput())->setValue($decodeResult->getValue()));
        $this->assertFalse($parseResult->getIsError());

        $sut = new Evaluator(new Config());
        $got = $sut->evaluateExpr((new EvaluateInput())->setExpr($parseResult->getExpr())->setDefStack(new DefStack()));

        if ($testcase->wantError) {
            $this->assertNotEquals(EvaluateOutput\Status::OK, $got->getStatus());
        } else {
            $this->assertEquals(EvaluateOutput\Status::OK, $got->getStatus());
            $msg = $this->checkValueEqual([], $testcase->wantValue, $got->getValue());
            if ($msg != null) {
                $this->fail($msg);
            }
        }
    }

    /**
     * @dataProvider provideTestEvaluate_Extension
     * @param EvaluatorExtensionTestcase $testcase
     * @return void
     */
    public function testEvaluate_Extension(EvaluatorExtensionTestcase $testcase)
    {
        $decodeResult = (new Decoder())->decode((new DecodeInput())->setYaml($testcase->inputYaml));
        $this->assertFalse($decodeResult->getIsError());

        $parseResult = (new Parser())->parse((new ParseInput())->setValue($decodeResult->getValue()));
        $this->assertFalse($parseResult->getIsError());

        $sut = new Evaluator((new Config())->setExtension([
            '$test_func' => function (Path $path, array $args): EvaluateOutput {
                return (new EvaluateOutput())->setValue((new Value())
                    ->setType(Type::OBJ)
                    ->setObj($args));
            }
        ]));
        $got = $sut->evaluateExpr((new EvaluateInput())->setExpr($parseResult->getExpr())->setDefStack(new DefStack()));
        $this->assertEquals(EvaluateOutput\Status::OK, $got->getStatus());
        $msg = $this->checkValueEqual([], $testcase->wantValue, $got->getValue());
        if ($msg != null) {
            $this->fail($msg);
        }
    }

    public function testEvaluate_BeforeEvaluate()
    {
        $evalPaths = [];
        $decodeResult = (new Decoder())->decode((new DecodeInput())->setYaml('cat: ["`Hello`", "`, `", "`ExprML`", "`!`"]'));
        $this->assertFalse($decodeResult->getIsError());

        $parseResult = (new Parser())->parse((new ParseInput())->setValue($decodeResult->getValue()));
        $this->assertFalse($parseResult->getIsError());

        $sut = new Evaluator((new Config())->setBeforeEvaluate(
            function (EvaluateInput $input) use (&$evalPaths): void {
                $evalPaths[] = \Exprml\Path::format($input->getExpr()->getPath());
            }
        ));
        $got = $sut->evaluateExpr((new EvaluateInput())->setExpr($parseResult->getExpr())->setDefStack(new DefStack()));
        $this->assertEquals(EvaluateOutput\Status::OK, $got->getStatus());
        $this->assertEquals([
            "/",
            "/cat/0",
            "/cat/1",
            "/cat/2",
            "/cat/3",
        ], $evalPaths);
    }

    public function testEvaluate_AfterEvaluate()
    {
        $evalTypes = [];
        $decodeResult = (new Decoder())->decode((new DecodeInput())->setYaml('cat: ["`Hello`", "`, `", "`ExprML`", "`!`"]'));
        $this->assertFalse($decodeResult->getIsError());

        $parseResult = (new Parser())->parse((new ParseInput())->setValue($decodeResult->getValue()));
        $this->assertFalse($parseResult->getIsError());

        $sut = new Evaluator((new Config())->setAfterEvaluate(
            function (EvaluateInput $input, EvaluateOutput $output) use (&$evalTypes): void {
                $evalTypes[] = $output->getValue()->getType();
            }
        ));
        $got = $sut->evaluateExpr((new EvaluateInput())->setExpr($parseResult->getExpr())->setDefStack(new DefStack()));
        $this->assertEquals(EvaluateOutput\Status::OK, $got->getStatus());
        $this->assertEquals([
            Type::STR,
            Type::STR,
            Type::STR,
            Type::STR,
            Type::STR,
        ], $evalTypes);
    }

    /**
     * @param string[] $path
     * @param Value $want
     * @param Value $got
     * @return string|null
     */
    private function checkValueEqual(array $path, Value $want, Value $got): ?string
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
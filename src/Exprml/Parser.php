<?php

namespace Exprml;

use Exception;
use Exprml\PB\Exprml\V1\Arr;
use Exprml\PB\Exprml\V1\Call;
use Exprml\PB\Exprml\V1\Cases;
use Exprml\PB\Exprml\V1\Cases\PBCase;
use Exprml\PB\Exprml\V1\Elem;
use Exprml\PB\Exprml\V1\PBEval\Definition;
use Exprml\PB\Exprml\V1\Expr;
use Exprml\PB\Exprml\V1\Expr\Kind;
use Exprml\PB\Exprml\V1\Expr\Path as PBPath;
use Exprml\PB\Exprml\V1\Iter;
use Exprml\PB\Exprml\V1\Json;
use Exprml\PB\Exprml\V1\Obj;
use Exprml\PB\Exprml\V1\OpBinary;
use Exprml\PB\Exprml\V1\OpUnary;
use Exprml\PB\Exprml\V1\OpVariadic;
use Exprml\PB\Exprml\V1\OpVariadic\Op;
use Exprml\PB\Exprml\V1\ParseInput;
use Exprml\PB\Exprml\V1\ParseOutput;
use Exprml\PB\Exprml\V1\PBEval;
use Exprml\PB\Exprml\V1\Ref;
use Exprml\PB\Exprml\V1\Scalar;
use Exprml\PB\Exprml\V1\Value as PBValue;
use Exprml\PB\Exprml\V1\Value\Type as PBType;

class Parser
{
    public function parse(ParseInput $input): ParseOutput
    {
        try {
            $expr = $this->parseImpl(new PBPath(), $input->getValue());
            return (new ParseOutput())->setExpr($expr);
        } catch (Exception $e) {
            return (new ParseOutput())
                ->setIsError(true)
                ->setErrorMessage(sprintf("fail to parse: %v", $e->getMessage()));
        }
    }


    private static string $identRegexp = '/^\$[_a-zA-Z][_a-zA-Z0-9]*$/';

    /**
     * @throws Exception
     */
    private static function parseImpl(PBPath $path, PBValue $value): Expr
    {
        $expr = (new Expr())->setPath($path)->setValue($value);
        switch ($value->getType()) {
            case PBType::NUM:
            case PBType::BOOL:
                /** @var Scalar $scalar */
                $scalar = (new Scalar)->setScalar($value);
                return $expr
                    ->setKind(Kind::SCALAR)
                    ->setScalar($scalar);
            case PBType::STR:
                $s = $value->getStr();
                if (preg_match(Parser::$identRegexp, $s)) {
                    return $expr
                        ->setKind(Kind::REF)
                        ->setRef((new Ref())->setIdent($s));
                }
                if (preg_match('/^`.*`$/', $s)) {
                    /** @var Scalar $scalar */
                    $scalar = (new Scalar())
                        ->setScalar((new PBValue)
                            ->setType(PBType::STR)
                            ->setStr(substr($s, 1, strlen($s) - 2)));
                    return $expr
                        ->setKind(Kind::SCALAR)
                        ->setScalar($scalar);
                }
                throw new Exception(sprintf("invalid Scalar: %s: string literal must be enclosed by '`'", Path::format($path)));
            case PBType::OBJ:
                if (Parser::hasKey($value, 'eval')) {
                    /** @var PBValue $evalVal */
                    $evalVal = $value->getObj()["eval"];
                    $eval = (new PBEval)
                        ->setEval(Parser::parseImpl(Path::append($path, "eval"), $evalVal));
                    if (Parser::hasKey($value, "where")) {
                        $where = $value->getObj()["where"];
                        if ($where->getType() !== PBType::ARR) {
                            throw new Exception(sprintf("invalid Expr: %s: where clause must be an array", Path::format(Path::append($path, "where"))));
                        }

                        $defs = [];
                        foreach ($where->getArr() as $i => /** @var PBValue $def */ $def) {
                            if ($def->getType() !== PBType::OBJ) {
                                throw new Exception(sprintf("invalid definition: %s: where clause must contain only objects but got %s", Path::format(Path::append($path, "where", $i)), $def->getType()));
                            }

                            $keys = array_keys($def->getObj());
                            if (count($keys) !== 1) {
                                throw new Exception(sprintf("invalid definition: %s: definition must contain one property", Path::format(Path::append($path, "where", $i))));
                            }

                            $prop = $keys[0];
                            $r = '/^\$[_a-zA-Z][_a-zA-Z0-9]*(\(\s*\)|\(\s*\$[_a-zA-Z][_a-zA-Z0-9]*(\s*,\s*\$[_a-zA-Z][_a-zA-Z0-9]*)*(\s*,)?\s*\))?$/';
                            if (!preg_match($r, $prop)) {
                                throw new Exception(sprintf("invalid definition: %s: definition must match %s", Path::format(Path::append($path, "where", $i, $prop)), $r));
                            }

                            $idents = [];
                            foreach (str_split($prop) as $char) {
                                if ($char === '$') {
                                    $idents[] = $char;
                                } elseif (ctype_alnum($char) || $char === '_') {
                                    $idents[count($idents) - 1] .= $char;
                                }
                            }

                            $body = Parser::parseImpl(Path::append($path, "where", $i, $prop), $def->getObj()[$prop]);

                            $defs[] = (new Definition())
                                ->setIdent($idents[0])
                                ->setArgs(array_slice($idents, 1))
                                ->setBody($body);
                        }
                        $eval->setWhere($defs);
                    }

                    return $expr
                        ->setKind(Kind::PBEVAL)
                        ->setEval($eval);
                }
                if (Parser::hasKey($value, 'obj')) {
                    if ($value->getObj()["obj"]->getType() !== PBType::OBJ) {
                        throw new Exception(sprintf("invalid Obj: %s: 'obj' property must be an object", Path::format(Path::append($path, "obj"))));
                    }
                    $obj = [];
                    foreach ($value->getObj()["obj"]->getObj() as $key => $val) {
                        $obj[$key] = Parser::parseImpl(Path::append($path, "obj", $key), $val);
                    }
                    return $expr
                        ->setKind(Kind::OBJ)
                        ->setObj((new Obj())->setObj($obj));
                }
                if (Parser::hasKey($value, 'arr')) {
                    if ($value->getObj()["arr"]->getType() !== PBType::ARR) {
                        throw new Exception(sprintf("invalid Arr: %s: 'arr' property must be an array", Path::format(Path::append($path, "arr"))));
                    }
                    $arr = [];
                    foreach ($value->getObj()["arr"]->getArr() as $i => $val) {
                        $arr[] = Parser::parseImpl(Path::append($path, "arr", $i), $val);
                    }
                    return $expr
                        ->setKind(Kind::ARR)
                        ->setArr((new Arr())->setArr($arr));
                }
                if (Parser::hasKey($value, 'json')) {
                    /** @var PBValue $json */
                    $json = $value->getObj()["json"];
                    try {
                        Parser::checkNonNull($json);
                    } catch (Exception) {
                        throw new Exception(sprintf("invalid Json: %s: 'json' property cannot contain null", Path::format(Path::append($path, "json"))));
                    }
                    return $expr
                        ->setKind(Kind::JSON)
                        ->setJson((new Json())->setJson($json));
                }
                if (Parser::hasKey($value, 'do')) {
                    $iter = new Iter();

                    $r = '/^for\(\s*\$[_a-zA-Z][_a-zA-Z0-9]*\s*,\s*\$[_a-zA-Z][_a-zA-Z0-9]*\s*\)$/';

                    foreach (Value::keys($value) as $prop) {
                        switch (true) {
                            case $prop === "do":
                                /** @var PBValue $do */
                                $do = $value->getObj()["do"];
                                $iter->setDo(Parser::parseImpl(Path::append($path, "do"), $do));
                                break;
                            case $prop === "if":
                                /** @var PBValue $if */
                                $if = $value->getObj()["if"];
                                $iter->setIf(Parser::parseImpl(Path::append($path, "if"), $if));
                                break;
                            case preg_match($r, $prop):
                                $idents = [];
                                foreach (str_split(substr($prop, 4, -1)) as $char) {
                                    if ($char === '$') {
                                        $idents[] = $char;
                                    } elseif (ctype_alnum($char) || $char === '_') {
                                        $idents[count($idents) - 1] .= $char;
                                    }
                                }
                                $iter->setPosIdent($idents[0]);
                                $iter->setElemIdent($idents[1]);
                                /** @var PBValue $col */
                                $col = $value->getObj()[$prop];
                                $iter->setCol(Parser::parseImpl(Path::append($path, $prop), $col));
                                break;
                            default:
                                throw new Exception(sprintf("invalid Iter: %s: invalid property %s", Path::format(Path::append($path, "do", $prop)), $prop));
                        }
                    }
                    if ($iter->getCol() === null) {
                        throw new Exception(sprintf("invalid Iter: %s: 'for(...vars...)' property is required", Path::format($path)));
                    }
                    return $expr
                        ->setKind(Kind::ITER)
                        ->setIter($iter);
                }
                if (Parser::hasKey($value, 'get')) {
                    $elem = new Elem();
                    /** @var PBValue $get */
                    $get = $value->getObj()["get"];
                    $elem->setGet(Parser::parseImpl(Path::append($path, "get"), $get));
                    /** @var PBValue $from */
                    $from = $value->getObj()["from"];
                    $elem->setFrom(Parser::parseImpl(Path::append($path, "from"), $from));
                    return $expr
                        ->setKind(Kind::ELEM)
                        ->setElem($elem);
                }
                if (Parser::hasKey($value, 'cases')) {
                    /** @var PBValue $casesVal */
                    $casesVal = $value->getObj()["cases"];
                    if ($casesVal->getType() !== PBType::ARR) {
                        throw new Exception(sprintf("invalid Cases: %s: 'cases' property must be an array", Path::format(Path::append($path, "cases"))));
                    }
                    $cases = [];
                    foreach ($casesVal->getArr() as $i => $c) {
                        if ($c->getType() !== PBType::OBJ) {
                            throw new Exception(sprintf("invalid Case: %s: 'cases' property must contain only objects but got %s", Path::format(Path::append($path, "cases", $i)), $c->getType()));
                        }
                        if (Parser::hasKey($c, "otherwise")) {
                            $otherwise = Parser::parseImpl(Path::append($path, "cases", $i, "otherwise"), $c->getObj()["otherwise"]);
                            $cases[] = (new PBCase())
                                ->setIsOtherwise(true)
                                ->setOtherwise($otherwise);
                        } else {
                            if (!Parser::hasKey($c, "when")) {
                                throw new Exception(sprintf("invalid Case: %s: 'when' property is required", Path::format(Path::append($path, "cases", $i))));
                            }
                            $when = Parser::parseImpl(Path::append($path, "cases", $i, "when"), $c->getObj()["when"]);
                            if (!Parser::hasKey($c, "then")) {
                                throw new Exception(sprintf("invalid Case: %s: 'then' property is required", Path::format(Path::append($path, "cases", $i))));
                            }
                            $then = Parser::parseImpl(Path::append($path, "cases", $i, "then"), $c->getObj()["then"]);
                            $cases[] = (new PBCase())
                                ->setWhen($when)
                                ->setThen($then);
                        }
                    }
                    return $expr
                        ->setKind(Kind::CASES)
                        ->setCases((new Cases())->setCases($cases));
                }

                // Call, OpUnary, OpBinary, or OpVariadic
                if (count($value->getObj()) !== 1) {
                    throw new Exception(sprintf("invalid Expr: %s: operation or function call must contain only one property", Path::format($path)));
                }
                $prop = Value::keys($value)[0];

                $operatorsUnary = [
                    "len" => OpUnary\Op::LEN,
                    "not" => OpUnary\Op::NOT,
                    "flat" => OpUnary\Op::FLAT,
                    "floor" => OpUnary\Op::FLOOR,
                    "ceil" => OpUnary\Op::CEIL,
                    "abort" => OpUnary\Op::ABORT,
                ];
                if (array_key_exists($prop, $operatorsUnary)) {
                    $expr->setKind(Kind::OP_UNARY);
                    /** @var PBValue $operandVal */
                    $operandVal = $value->getObj()[$prop];
                    return $expr
                        ->setKind(Kind::OP_UNARY)
                        ->setOpUnary((new OpUnary)
                            ->setOp($operatorsUnary[$prop])
                            ->setOperand(Parser::parseImpl(Path::append($path, $prop), $operandVal)));
                }

                $operatorsBinary = [
                    "sub" => OpBinary\Op::SUB,
                    "div" => OpBinary\Op::DIV,
                    "eq" => OpBinary\Op::EQ,
                    "neq" => OpBinary\Op::NEQ,
                    "lt" => OpBinary\Op::LT,
                    "lte" => OpBinary\Op::LTE,
                    "gt" => OpBinary\Op::GT,
                    "gte" => OpBinary\Op::GTE,
                ];
                if (array_key_exists($prop, $operatorsBinary)) {
                    /** @var PBValue $operandsVal */
                    $operandsVal = $value->getObj()[$prop];
                    if ($operandsVal->getType() !== PBType::ARR) {
                        throw new Exception(sprintf("invalid OpBinary: %s: '%s' property must be an array", Path::format(Path::append($path, $prop)), $prop));
                    }
                    if (count($value->getObj()[$prop]->getArr()) !== 2) {
                        throw new Exception(sprintf("invalid OpBinary: %s: '%s' property must contain two elements", Path::format(Path::append($path, $prop)), $prop));
                    }
                    return $expr
                        ->setKind(Kind::OP_BINARY)
                        ->setOpBinary((new OpBinary())
                            ->setOp($operatorsBinary[$prop])
                            ->setLeft(Parser::parseImpl(Path::append($path, $prop, 0), $value->getObj()[$prop]->getArr()[0]))
                            ->setRight(Parser::parseImpl(Path::append($path, $prop, 1), $value->getObj()[$prop]->getArr()[1])));
                }

                $operatorsVariadic = [
                    "add" => OpVariadic\Op::ADD,
                    "mul" => OpVariadic\Op::MUL,
                    "and" => OpVariadic\Op::PBAND,
                    "or" => OpVariadic\Op::PBOR,
                    "cat" => OpVariadic\Op::CAT,
                    "min" => OpVariadic\Op::MIN,
                    "max" => OpVariadic\Op::MAX,
                    "merge" => OpVariadic\Op::MERGE,
                ];
                if (array_key_exists($prop, $operatorsVariadic)) {
                    if ($value->getObj()[$prop]->getType() !== PBType::ARR) {
                        throw new Exception(sprintf("invalid OpVariadic: %s: '%s' property must be an array", Path::format(Path::append($path, $prop)), $prop));
                    }
                    if (in_array($prop, ["min", "max"]) && count($value->getObj()[$prop]->getArr()) === 0) {
                        throw new Exception(sprintf("invalid OpVariadic: %s: '%s' property must contain at least one element", Path::format(Path::append($path, $prop)), $prop));
                    }
                    $operands = [];
                    foreach ($value->getObj()[$prop]->getArr() as $i => $v) {
                        $operands[] = Parser::parseImpl(Path::append($path, $prop, $i), $v);
                    }
                    return $expr
                        ->setKind(Kind::OP_VARIADIC)
                        ->setOpVariadic((new OpVariadic())
                            ->setOp($operatorsVariadic[$prop])
                            ->setOperands($operands));
                }

                if (!preg_match(Parser::$identRegexp, $prop)) {
                    throw new Exception(sprintf("invalid Call: %s: function call property '%s' must match '%s'", Path::format($path), $prop, Parser::$identRegexp));
                }

                /** @var PBValue $args */
                $argsVal = $value->getObj()[$prop];
                if ($argsVal->getType() !== PBType::OBJ) {
                    throw new Exception(sprintf("invalid Call: %s: arguments must be given as an object", Path::format(Path::append($path, $prop))));
                }
                $args = [];
                foreach ($argsVal->getObj() as $key => $val) {
                    if (!preg_match(Parser::$identRegexp, $key)) {
                        throw new Exception(sprintf("invalid Call: %s: argument property '%s' must match '%s'", Path::format(Path::append($path, $prop, $key)), $key, Parser::$identRegexp));
                    }
                    $args[$key] = Parser::parseImpl(Path::append($path, $prop, $key), $val);
                }
                return $expr
                    ->setKind(Kind::CALL)
                    ->setCall((new Call())->setIdent($prop)->setArgs($args));
            default:
                throw new Exception(sprintf("invalid Expr: %s: one of string, number, boolean, or object required but got %s", Path::format($path), PBType::name($value->getType())));
        }
    }

    private static function hasKey(PBValue $value, string $key): bool
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $value->getType() === PBType::OBJ && $value->getObj()->offsetExists($key);
    }

    /**
     * @throws Exception
     */
    private static function checkNonNull(PBValue $value): void
    {
        if ($value->getType() === PBType::NULL) {
            throw new Exception("null value is not allowed");
        }
        foreach ($value->getObj() as $v) {
            Parser::checkNonNull($v);
        }
        foreach ($value->getArr() as $v) {
            Parser::checkNonNull($v);
        }
    }

}
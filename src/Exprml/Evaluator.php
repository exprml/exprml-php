<?php

namespace Exprml;

use Exception;
use Exprml\Evaluator\Config;
use Exprml\PB\Exprml\V1\EvaluateInput;
use Exprml\PB\Exprml\V1\EvaluateOutput;
use Exprml\PB\Exprml\V1\Expr;
use Exprml\PB\Exprml\V1\Expr\Kind;
use Exprml\PB\Exprml\V1\Expr\Path as PBPath;
use Exprml\PB\Exprml\V1\PBEval\Definition;
use Exprml\PB\Exprml\V1\Scalar as PBScalar;
use Exprml\PB\Exprml\V1\Value as PBValue;
use Exprml\PB\Exprml\V1\OpUnary\Op as PBOpUnary;
use Exprml\PB\Exprml\V1\OpBinary\Op as PBOpBinary;
use Exprml\PB\Exprml\V1\OpVariadic\Op as PBOpVariadic;
use Exprml\PB\Exprml\V1\Value\Type;


class Evaluator
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function evaluateExpr(EvaluateInput $input): EvaluateOutput
    {
        try {
            $this->config->getBeforeEvaluate()($input);
        } catch (Exception $err) {
            return (new EvaluateOutput())
                ->setStatus(EvaluateOutput\Status::UNKNOWN_ERROR)
                ->setErrorPath($input->getExpr()->getPath())
                ->setErrorMessage(sprintf("BeforeEvaluate failed: %s", $err->getMessage()));
        }

        switch ($input->getExpr()->getKind()) {
            case Kind::PBEVAL:
                $output = $this->evaluateEval($input);
                break;
            case Kind::SCALAR:
                $output = $this->evaluateScalar($input);
                break;
            case Kind::REF:
                $output = $this->evaluateRef($input);
                break;
            case Kind::OBJ:
                $output = $this->evaluateObj($input);
                break;
            case Kind::ARR:
                $output = $this->evaluateArr($input);
                break;
            case Kind::JSON:
                $output = $this->evaluateJson($input);
                break;
            case Kind::ITER:
                $output = $this->evaluateIter($input);
                break;
            case Kind::ELEM:
                $output = $this->evaluateElem($input);
                break;
            case Kind::CALL:
                $output = $this->evaluateCall($input);
                break;
            case Kind::CASES:
                $output = $this->evaluateCases($input);
                break;
            case Kind::OP_UNARY:
                $output = $this->evaluateOpUnary($input);
                break;
            case Kind::OP_BINARY:
                $output = $this->evaluateOpBinary($input);
                break;
            case Kind::OP_VARIADIC:
                $output = $this->evaluateOpVariadic($input);
                break;
            default:
                assert(false, new Exception("given expression must be validated"));
        }

        try {
            $this->config->getAfterEvaluate()($input, $output);
        } catch (Exception $err) {
            return (new EvaluateOutput())
                ->setStatus(EvaluateOutput\Status::UNKNOWN_ERROR)
                ->setErrorPath($input->getExpr()->getPath())
                ->setErrorMessage(sprintf("AfterEvaluate failed: %s", $err->getMessage()));
        }

        return $output;
    }

    public function evaluateEval(EvaluateInput $input): EvaluateOutput
    {
        $st = $input->getDefStack();
        $where = $input->getExpr()->getEval()->getWhere();

        foreach ($where as /** @var Definition $def */ $def) {
            $st = DefStack::register($st, $def);
        }

        return $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($st)
            ->setExpr($input->getExpr()->getEval()->getEval())
        );
    }

    public function evaluateScalar(EvaluateInput $input): EvaluateOutput
    {
        /** @var PBScalar $scalar */
        $scalar = $input->getExpr()->getScalar();
        return (new EvaluateOutput())
            ->setValue($scalar->getScalar());
    }

    public function evaluateRef(EvaluateInput $input): EvaluateOutput
    {
        $ref = $input->getExpr()->getRef();
        $st = DefStack::find($input->getDefStack(), $ref->getIdent());

        if ($st === null) {
            if (!array_key_exists($ref->getIdent(), $this->config->getExtension())) {
                return Evaluator::errorReferenceNotFound($input->getExpr()->getPath(), $ref->getIdent());
            }
            $ext = $this->config->getExtension()[$ref->getIdent()];
            return $ext($input->getExpr()->getPath(), []);
        }

        return $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($st)
            ->setExpr($st->getDef()->getBody())
        );
    }

    public function evaluateObj(EvaluateInput $input): EvaluateOutput
    {
        $result = [];
        foreach ($input->getExpr()->getObj()->getObj() as $pos => $expr) {
            $val = $this->evaluateExpr((new EvaluateInput())
                ->setDefStack($input->getDefStack())
                ->setExpr($expr)
            );
            if ($val->getStatus() !== EvaluateOutput\Status::OK) {
                return $val;
            }
            $result[$pos] = $val->getValue();
        }
        return (new EvaluateOutput())->setValue(Value::obj($result));
    }

    public function evaluateArr(EvaluateInput $input): EvaluateOutput
    {
        $result = [];
        foreach ($input->getExpr()->getArr()->getArr() as $expr) {
            $val = $this->evaluateExpr((new EvaluateInput())
                ->setDefStack($input->getDefStack())
                ->setExpr($expr)
            );
            if ($val->getStatus() !== EvaluateOutput\Status::OK) {
                return $val;
            }
            $result[] = $val->getValue();
        }
        return (new EvaluateOutput())->setValue(Value::arr($result));
    }

    public function evaluateJson(EvaluateInput $input): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setValue($input->getExpr()->getJson()->getJson());
    }

    public function evaluateIter(EvaluateInput $input): EvaluateOutput
    {
        $iter = $input->getExpr()->getIter();
        $forPos = $iter->getPosIdent();
        $forElem = $iter->getElemIdent();
        $inVal = $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($input->getDefStack())
            ->setExpr($iter->getCol())
        );

        switch ($inVal->getValue()->getType()) {
            case Type::STR:
                $result = [];
                foreach (mb_str_split($inVal->getValue()->getStr()) as $i => $c) {
                    $st = $input->getDefStack();
                    $st = DefStack::register($st, DefStack::newDefinition($input->getExpr()->getPath(), $forPos, Value::num((float)$i)));
                    $st = DefStack::register($st, DefStack::newDefinition($input->getExpr()->getPath(), $forElem, Value::str($c)));
                    if ($iter->hasIf()) {
                        $ifVal = $this->evaluateExpr((new EvaluateInput())
                            ->setDefStack($st)
                            ->setExpr($iter->getIf())
                        );
                        if ($ifVal->getStatus() !== EvaluateOutput\Status::OK) {
                            return $ifVal;
                        }
                        if ($ifVal->getValue()->getType() !== Type::BOOL) {
                            return Evaluator::errorUnexpectedType($iter->getIf()->getPath(), $ifVal->getValue()->getType(), [Type::BOOL]);
                        }
                        if (!$ifVal->getValue()->getBool()) {
                            continue;
                        }
                    }
                    $v = $this->evaluateExpr((new EvaluateInput())
                        ->setDefStack($st)
                        ->setExpr($iter->getDo())
                    );
                    if ($v->getStatus() !== EvaluateOutput\Status::OK) {
                        return $v;
                    }
                    $result[] = $v->getValue();
                }
                return (new EvaluateOutput())->setValue(Value::arr($result));
            case Type::ARR:
                $result = [];
                foreach ($inVal->getValue()->getArr() as $i => $elemVal) {
                    $st = $input->getDefStack();
                    $st = DefStack::register($st, DefStack::newDefinition($input->getExpr()->getPath(), $forPos, Value::num((float)$i)));
                    $st = DefStack::register($st, DefStack::newDefinition($input->getExpr()->getPath(), $forElem, $elemVal));
                    if ($iter->hasIf()) {
                        $ifVal = $this->evaluateExpr((new EvaluateInput())
                            ->setDefStack($st)
                            ->setExpr($iter->getIf())
                        );
                        if ($ifVal->getStatus() !== EvaluateOutput\Status::OK) {
                            return $ifVal;
                        }
                        if ($ifVal->getValue()->getType() !== Type::BOOL) {
                            return Evaluator::errorUnexpectedType($iter->getIf()->getPath(), $ifVal->getValue()->getType(), [Type::BOOL]);
                        }
                        if (!$ifVal->getValue()->getBool()) {
                            continue;
                        }
                    }
                    $v = $this->evaluateExpr((new EvaluateInput())
                        ->setDefStack($st)
                        ->setExpr($iter->getDo())
                    );
                    if ($v->getStatus() !== EvaluateOutput\Status::OK) {
                        return $v;
                    }
                    $result[] = $v->getValue();
                }
                return (new EvaluateOutput())->setValue(Value::arr($result));
            case Type::OBJ:
                $result = [];
                foreach (Evaluator::sortedKeys(iterator_to_array($inVal->getValue()->getObj())) as $key) {
                    /** @var PBValue $val */
                    $val = $inVal->getValue()->getObj()->offsetGet($key);
                    $st = $input->getDefStack();
                    $st = DefStack::register($st, DefStack::newDefinition($input->getExpr()->getPath(), $forPos, Value::str($key)));
                    $st = DefStack::register($st, DefStack::newDefinition($input->getExpr()->getPath(), $forElem, $val));
                    if ($iter->hasIf()) {
                        $ifVal = $this->evaluateExpr((new EvaluateInput())
                            ->setDefStack($st)
                            ->setExpr($iter->getIf())
                        );
                        if ($ifVal->getStatus() !== EvaluateOutput\Status::OK) {
                            return $ifVal;
                        }
                        if ($ifVal->getValue()->getType() !== Type::BOOL) {
                            return Evaluator::errorUnexpectedType($iter->getIf()->getPath(), $ifVal->getValue()->getType(), [Type::BOOL]);
                        }
                        if (!$ifVal->getValue()->getBool()) {
                            continue;
                        }
                    }
                    $v = $this->evaluateExpr((new EvaluateInput())
                        ->setDefStack($st)
                        ->setExpr($iter->getDo())
                    );
                    if ($v->getStatus() !== EvaluateOutput\Status::OK) {
                        return $v;
                    }
                    $result[$key] = $v->getValue();
                }
                return (new EvaluateOutput())->setValue(Value::obj($result));
            default:
                return Evaluator::errorUnexpectedType($iter->getCol()->getPath(), $inVal->getValue()->getType(), [Type::STR, Type::ARR, Type::OBJ]);
        }
    }

    public function evaluateElem(EvaluateInput $input): EvaluateOutput
    {
        $elem = $input->getExpr()->getElem();
        $getVal = $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($input->getDefStack())
            ->setExpr($elem->getGet())
        );
        if ($getVal->getStatus() !== EvaluateOutput\Status::OK) {
            return $getVal;
        }
        $pos = $getVal->getValue();
        $fromVal = $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($input->getDefStack())
            ->setExpr($elem->getFrom())
        );
        if ($fromVal->getStatus() !== EvaluateOutput\Status::OK) {
            return $fromVal;
        }
        $col = $fromVal->getValue();

        switch ($col->getType()) {
            case Type::STR:
                if ($pos->getType() !== Type::NUM) {
                    return Evaluator::errorUnexpectedType($elem->getGet()->getPath(), $pos->getType(), [Type::NUM]);
                }
                if (!Evaluator::canInt($pos)) {
                    return Evaluator::errorIndexNotInteger($elem->getGet()->getPath(), $pos->getNum());
                }
                $idx = (int)$pos->getNum();
                if ($idx < 0 || $idx >= mb_strlen($col->getStr())) {
                    return Evaluator::errorIndexOutOfBounds($elem->getGet()->getPath(), $pos, 0, mb_strlen($col->getStr()));
                }
                return (new EvaluateOutput())->setValue(Value::str(mb_substr($col->getStr(), $idx, 1)));
            case Type::ARR:
                if ($pos->getType() !== Type::NUM) {
                    return Evaluator::errorUnexpectedType($elem->getGet()->getPath(), $pos->getType(), [Type::NUM]);
                }
                if (!Evaluator::canInt($pos)) {
                    return Evaluator::errorIndexNotInteger($elem->getGet()->getPath(), $pos->getNum());
                }
                $idx = (int)$pos->getNum();
                if ($idx < 0 || $idx >= count($col->getArr())) {
                    return Evaluator::errorIndexOutOfBounds($elem->getGet()->getPath(), $pos, 0, count($col->getArr()));
                }
                return (new EvaluateOutput())->setValue($col->getArr()[$idx]);
            case Type::OBJ:
                if ($pos->getType() !== Type::STR) {
                    return Evaluator::errorUnexpectedType($elem->getGet()->getPath(), $pos->getType(), [Type::STR]);
                }
                $key = $pos->getStr();
                if (!array_key_exists($key, iterator_to_array($col->getObj()))) {
                    return Evaluator::errorInvalidKey($elem->getGet()->getPath(), $key, Evaluator::sortedKeys(iterator_to_array($col->getObj())));
                }
                /** @var PBValue $val */
                $val = $col->getObj()[$key];
                return (new EvaluateOutput())->setValue($val);
            default:
                return Evaluator::errorUnexpectedType($elem->getFrom()->getPath(), $col->getType(), [Type::STR, Type::ARR, Type::OBJ]);
        }
    }

    public function evaluateCall(EvaluateInput $input): EvaluateOutput
    {
        $call = $input->getExpr()->getCall();
        $st = DefStack::find($input->getDefStack(), $call->getIdent());

        if ($st === null) {
            if (!array_key_exists($call->getIdent(), $this->config->getExtension())) {
                return Evaluator::errorReferenceNotFound($input->getExpr()->getPath(), $call->getIdent());
            }
            $ext = $this->config->getExtension()[$call->getIdent()];
            $args = [];
            foreach ($call->getArgs() as $argName => $argExpr) {
                $argVal = $this->evaluateExpr((new EvaluateInput())
                    ->setDefStack($input->getDefStack())
                    ->setExpr($argExpr)
                );
                if ($argVal->getStatus() !== EvaluateOutput\Status::OK) {
                    return $argVal;
                }
                $args[$argName] = $argVal->getValue();
            }
            return $ext($input->getExpr()->getPath(), $args);
        }

        $def = $st->getDef();
        foreach ($def->getArgs() as $argName) {
            if (!$call->getArgs()->offsetExists($argName)) {
                return Evaluator::errorArgumentMismatch($input->getExpr()->getPath(), $argName);
            }
            /** @var Expr $arg */
            $arg = $call->getArgs()[$argName];
            $argVal = $this->evaluateExpr((new EvaluateInput())
                ->setDefStack($input->getDefStack())
                ->setExpr($arg)
            );
            if ($argVal->getStatus() !== EvaluateOutput\Status::OK) {
                return $argVal;
            }
            $st = DefStack::register($st, DefStack::newDefinition(
                Path::append($input->getExpr()->getPath(), $call->getIdent(), $argName),
                $argName,
                $argVal->getValue()
            ));
        }

        return $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($st)
            ->setExpr($def->getBody())
        );
    }

    public function evaluateCases(EvaluateInput $input): EvaluateOutput
    {
        $cases = $input->getExpr()->getCases()->getCases();
        foreach ($cases as $case) {
            if ($case->getIsOtherwise()) {
                return $this->evaluateExpr((new EvaluateInput())
                    ->setDefStack($input->getDefStack())
                    ->setExpr($case->getOtherwise())
                );
            } else {
                $boolVal = $this->evaluateExpr((new EvaluateInput())
                    ->setDefStack($input->getDefStack())
                    ->setExpr($case->getWhen())
                );
                if ($boolVal->getStatus() !== EvaluateOutput\Status::OK) {
                    return $boolVal;
                }
                if ($boolVal->getValue()->getType() !== Type::BOOL) {
                    return Evaluator::errorUnexpectedType($case->getWhen()->getPath(), $boolVal->getValue()->getType(), [Type::BOOL]);
                }
                if ($boolVal->getValue()->getBool()) {
                    return $this->evaluateExpr((new EvaluateInput())
                        ->setDefStack($input->getDefStack())
                        ->setExpr($case->getThen())
                    );
                }
            }
        }
        return Evaluator::errorCasesNotExhaustive(Path::append($input->getExpr()->getPath(), "cases"));
    }

    public function evaluateOpUnary(EvaluateInput $input): EvaluateOutput
    {
        $op = $input->getExpr()->getOpUnary();
        $o = $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($input->getDefStack())
            ->setExpr($op->getOperand())
        );
        if ($o->getStatus() !== EvaluateOutput\Status::OK) {
            return $o;
        }
        $operand = $o->getValue();

        switch ($op->getOp()) {
            case PBOpUnary::LEN:
                switch ($operand->getType()) {
                    case Type::STR:
                        return (new EvaluateOutput())->setValue(Value::num(mb_strlen($operand->getStr())));
                    case Type::ARR:
                        return (new EvaluateOutput())->setValue(Value::num(count($operand->getArr())));
                    case Type::OBJ:
                        return (new EvaluateOutput())->setValue(Value::num(count($operand->getObj())));
                    default:
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "len"), $operand->getType(), [Type::STR, Type::ARR, Type::OBJ]);
                }
            case PBOpUnary::NOT:
                if ($operand->getType() !== Type::BOOL) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "not"), $operand->getType(), [Type::BOOL]);
                }
                return (new EvaluateOutput())->setValue(Value::bool(!$operand->getBool()));
            case PBOpUnary::FLAT:
                if ($operand->getType() !== Type::ARR) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "flat"), $operand->getType(), [Type::ARR]);
                }
                $v = [];
                foreach ($operand->getArr() as $elem) {
                    if ($elem->getType() !== Type::ARR) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "flat"), $elem->getType(), [Type::ARR]);
                    }
                    $v = array_merge($v, iterator_to_array($elem->getArr()));
                }
                return (new EvaluateOutput())->setValue(Value::arr($v));
            case PBOpUnary::FLOOR:
                if ($operand->getType() !== Type::NUM) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "floor"), $operand->getType(), [Type::NUM]);
                }
                return (new EvaluateOutput())->setValue(Value::num(floor($operand->getNum())));
            case PBOpUnary::CEIL:
                if ($operand->getType() !== Type::NUM) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "ceil"), $operand->getType(), [Type::NUM]);
                }
                return (new EvaluateOutput())->setValue(Value::num(ceil($operand->getNum())));
            case PBOpUnary::ABORT:
                if ($operand->getType() !== Type::STR) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "abort"), $operand->getType(), [Type::STR]);
                }
                return (new EvaluateOutput())->setStatus(EvaluateOutput\Status::ABORTED)->setErrorMessage($operand->getStr());
            default:
                assert(false, new Exception(sprintf("unexpected unary operator %s", $op->getOp())));
        }
    }

    public function evaluateOpBinary(EvaluateInput $input): EvaluateOutput
    {
        $op = $input->getExpr()->getOpBinary();
        $ol = $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($input->getDefStack())
            ->setExpr($op->getLeft())
        );
        if ($ol->getStatus() !== EvaluateOutput\Status::OK) {
            return $ol;
        }
        $or = $this->evaluateExpr((new EvaluateInput())
            ->setDefStack($input->getDefStack())
            ->setExpr($op->getRight())
        );
        if ($or->getStatus() !== EvaluateOutput\Status::OK) {
            return $or;
        }
        $operandL = $ol->getValue();
        $operandR = $or->getValue();

        switch ($op->getOp()) {
            case PBOpBinary::SUB:
                if ($operandL->getType() !== Type::NUM) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "sub", 0), $operandL->getType(), [Type::NUM]);
                }
                if ($operandR->getType() !== Type::NUM) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "sub", 1), $operandR->getType(), [Type::NUM]);
                }
                $v = $operandL->getNum() - $operandR->getNum();
                if (!is_finite($v)) {
                    return Evaluator::errorNotFiniteNumber($input->getExpr()->getPath());
                }
                return (new EvaluateOutput())->setValue(Value::num($v));
            case PBOpBinary::DIV:
                if ($operandL->getType() !== Type::NUM) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "div", 0), $operandL->getType(), [Type::NUM]);
                }
                if ($operandR->getType() !== Type::NUM) {
                    return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "div", 1), $operandR->getType(), [Type::NUM]);
                }
                $v = Evaluator::computeDiv($operandL->getNum(), $operandR->getNum());
                if (!is_finite($v)) {
                    return Evaluator::errorNotFiniteNumber($input->getExpr()->getPath());
                }
                return (new EvaluateOutput())->setValue(Value::num($v));
            case PBOpBinary::EQ:
                return (new EvaluateOutput())->setValue(Evaluator::equal($operandL, $operandR));
            case PBOpBinary::NEQ:
                return (new EvaluateOutput())->setValue(Value::bool(!Evaluator::equal($operandL, $operandR)->getBool()));
            case PBOpBinary::LT:
                $cmpVal = Evaluator::compare(Path::append($input->getExpr()->getPath(), "lt"), $operandL, $operandR);
                if ($cmpVal->getStatus() !== EvaluateOutput\Status::OK) {
                    return $cmpVal;
                }
                return (new EvaluateOutput())->setValue(Value::bool($cmpVal->getValue()->getNum() < 0));
            case PBOpBinary::LTE:
                $cmpVal = Evaluator::compare(Path::append($input->getExpr()->getPath(), "lte"), $operandL, $operandR);
                if ($cmpVal->getStatus() !== EvaluateOutput\Status::OK) {
                    return $cmpVal;
                }
                return (new EvaluateOutput())->setValue(Value::bool($cmpVal->getValue()->getNum() <= 0));
            case PBOpBinary::GT:
                $cmpVal = Evaluator::compare(Path::append($input->getExpr()->getPath(), "gt"), $operandL, $operandR);
                if ($cmpVal->getStatus() !== EvaluateOutput\Status::OK) {
                    return $cmpVal;
                }
                return (new EvaluateOutput())->setValue(Value::bool($cmpVal->getValue()->getNum() > 0));
            case PBOpBinary::GTE:
                $cmpVal = Evaluator::compare(Path::append($input->getExpr()->getPath(), "gte"), $operandL, $operandR);
                if ($cmpVal->getStatus() !== EvaluateOutput\Status::OK) {
                    return $cmpVal;
                }
                return (new EvaluateOutput())->setValue(Value::bool($cmpVal->getValue()->getNum() >= 0));
            default:
                assert(false, new Exception(sprintf("unexpected binary operator %s", $op->getOp())));
        }
    }

    public function evaluateOpVariadic(EvaluateInput $input): EvaluateOutput
    {
        $op = $input->getExpr()->getOpVariadic();
        $operands = [];
        foreach ($op->getOperands() as $elem) {
            $val = $this->evaluateExpr((new EvaluateInput())
                ->setDefStack($input->getDefStack())
                ->setExpr($elem)
            );
            if ($val->getStatus() !== EvaluateOutput\Status::OK) {
                return $val;
            }
            $operands[] = $val->getValue();
        }

        switch ($op->getOp()) {
            case PBOpVariadic::ADD:
                $addVal = 0.0;
                foreach ($operands as $i => $operand) {
                    if ($operand->getType() !== PBValue\Type::NUM) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "add", $i), $operand->getType(), [PBValue\Type::NUM]);
                    }
                    $addVal += $operand->getNum();
                }
                if (!is_finite($addVal)) {
                    return Evaluator::errorNotFiniteNumber(Path::append($input->getExpr()->getPath(), "add"));
                }
                return (new EvaluateOutput())->setValue(Value::num($addVal));
            case PBOpVariadic::MUL:
                $mulVal = 1.0;
                foreach ($operands as $i => $operand) {
                    if ($operand->getType() !== PBValue\Type::NUM) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "mul", $i), $operand->getType(), [PBValue\Type::NUM]);
                    }
                    $mulVal *= $operand->getNum();
                }
                if (!is_finite($mulVal)) {
                    return Evaluator::errorNotFiniteNumber(Path::append($input->getExpr()->getPath(), "mul"));
                }
                return (new EvaluateOutput())->setValue(Value::num($mulVal));
            case PBOpVariadic::PBAND:
                foreach ($operands as $i => $operand) {
                    if ($operand->getType() !== PBValue\Type::BOOL) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "and", $i), $operand->getType(), [PBValue\Type::BOOL]);
                    }
                    if (!$operand->getBool()) {
                        return (new EvaluateOutput())->setValue(Value::bool(false));
                    }
                }
                return (new EvaluateOutput())->setValue(Value::bool(true));
            case PBOpVariadic::PBOR:
                foreach ($operands as $i => $operand) {
                    if ($operand->getType() !== PBValue\Type::BOOL) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "or", $i), $operand->getType(), [PBValue\Type::BOOL]);
                    }
                    if ($operand->getBool()) {
                        return (new EvaluateOutput())->setValue(Value::bool(true));
                    }
                }
                return (new EvaluateOutput())->setValue(Value::bool(false));
            case PBOpVariadic::CAT:
                $catVal = "";
                foreach ($operands as $i => $operand) {
                    if ($operand->getType() !== PBValue\Type::STR) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "cat", $i), $operand->getType(), [PBValue\Type::STR]);
                    }
                    $catVal .= $operand->getStr();
                }
                return (new EvaluateOutput())->setValue(Value::str($catVal));
            case PBOpVariadic::MIN:
                $minVal = INF;
                foreach ($operands as $i => $operand) {
                    if ($operand->getType() !== PBValue\Type::NUM) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "min", $i), $operand->getType(), [PBValue\Type::NUM]);
                    }
                    $minVal = min($minVal, $operand->getNum());
                }
                return (new EvaluateOutput())->setValue(Value::num($minVal));
            case PBOpVariadic::MAX:
                $maxVal = -INF;
                foreach ($operands as $i => $operand) {
                    if ($operand->getType() !== PBValue\Type::NUM) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "max", $i), $operand->getType(), [PBValue\Type::NUM]);
                    }
                    $maxVal = max($maxVal, $operand->getNum());
                }
                return (new EvaluateOutput())->setValue(Value::num($maxVal));
            case PBOpVariadic::MERGE:
                $mergeVal = [];
                foreach ($operands as $i => $operand) {
                    if ($operand->getType() !== PBValue\Type::OBJ) {
                        return Evaluator::errorUnexpectedType(Path::append($input->getExpr()->getPath(), "merge", $i), $operand->getType(), [PBValue\Type::OBJ]);
                    }
                    foreach ($operand->getObj() as $k => $v) {
                        $mergeVal[$k] = $v;
                    }
                }
                return (new EvaluateOutput())->setValue(Value::obj($mergeVal));
            default:
                assert(false, new Exception(sprintf("unexpected variadic operator %s", $op->getOp())));
        }
    }

    private static function errorIndexOutOfBounds(PBPath $path, PBValue $index, int $begin, int $end): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::INVALID_INDEX)
            ->setErrorPath($path)
            ->setErrorMessage(sprintf("invalid index: index out of bounds: %d not in [%d, %d)", (int)$index->getNum(), $begin, $end));
    }

    private static function errorIndexNotInteger(PBPath $path, float $index): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::INVALID_INDEX)
            ->setErrorPath($path)
            ->setErrorMessage(sprintf("invalid index: non integer index: %f", $index));
    }

    private static function errorInvalidKey(PBPath $path, string $key, array $keys): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::INVALID_INDEX)
            ->setErrorPath($path)
            ->setErrorMessage(sprintf("invalid key: %s not in {%s}", $key, implode(",", $keys)));
    }

    private static function errorUnexpectedType(PBPath $path, int $got, array $want): EvaluateOutput
    {
        $wantStr = array_map(fn($t) => Type::name($t), $want);
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::UNEXPECTED_TYPE)
            ->setErrorPath($path)
            ->setErrorMessage(sprintf("unexpected type: got %s, want {%s}", Type::name($got), implode(",", $wantStr)));
    }

    private static function errorArgumentMismatch(PBPath $path, string $arg): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::ARGUMENT_MISMATCH)
            ->setErrorPath($path)
            ->setErrorMessage(sprintf("argument mismatch: argument %s required", $arg));
    }

    private static function errorReferenceNotFound(PBPath $path, string $ref): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::REFERENCE_NOT_FOUND)
            ->setErrorPath($path)
            ->setErrorMessage(sprintf("reference not found: %s", $ref));
    }

    private static function errorCasesNotExhaustive(PBPath $path): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::CASES_NOT_EXHAUSTIVE)
            ->setErrorPath($path)
            ->setErrorMessage("cases not exhaustive");
    }

    private static function errorNotComparable(PBPath $path): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::NOT_COMPARABLE)
            ->setErrorPath($path)
            ->setErrorMessage("not comparable");
    }

    private static function errorNotFiniteNumber(PBPath $path): EvaluateOutput
    {
        return (new EvaluateOutput())
            ->setStatus(EvaluateOutput\Status::NOT_FINITE_NUMBER)
            ->setErrorPath($path)
            ->setErrorMessage("not finite number");
    }

    private static function canInt(PBValue $v): bool
    {
        return $v->getType() === Type::NUM && $v->getNum() === (float)(int)$v->getNum();
    }

    private static function sortedKeys(array $m): array
    {
        $keys = array_keys($m);
        sort($keys);
        return $keys;
    }

    private static function equal(PBValue $l, PBValue $r): PBValue
    {
        $falseValue = Value::bool(false);
        $trueValue = Value::bool(true);

        switch (true) {
            case $l->getType() !== $r->getType():
                return $falseValue;
            case $l->getType() === Type::NUM:
                return Value::bool($l->getNum() === $r->getNum());
            case $l->getType() === Type::BOOL:
                return Value::bool($l->getBool() === $r->getBool());
            case $l->getType() === Type::STR:
                return Value::bool($l->getStr() === $r->getStr());
            case $l->getType() === Type::ARR:
                if (count($l->getArr()) !== count($r->getArr())) {
                    return $falseValue;
                }
                foreach ($l->getArr() as $i => $lv) {
                    $eq = Evaluator::equal($lv, $r->getArr()[$i]);
                    if (!$eq->getBool()) {
                        return $falseValue;
                    }
                }
                return $trueValue;
            case $l->getType() === Type::OBJ:
                $lk = Evaluator::sortedKeys(iterator_to_array($l->getObj()));
                $rk = Evaluator::sortedKeys(iterator_to_array($r->getObj()));
                if ($lk !== $rk) {
                    return $falseValue;
                }
                foreach ($l->getObj() as $k => $lv) {
                    /** @var PBValue $rv */
                    $rv = $r->getObj()[$k];
                    $eq = Evaluator::equal($lv, $rv);
                    if (!$eq->getBool()) {
                        return $falseValue;
                    }
                }
                return $trueValue;
            default:
                assert(false, new Exception(sprintf("unexpected type %s", Type::name($l->getType()))));
        }
    }

    private static function compare(PBPath $path, PBValue $l, PBValue $r): EvaluateOutput
    {
        $ltValue = (new EvaluateOutput())->setValue(Value::num(-1.0));
        $gtValue = (new EvaluateOutput())->setValue(Value::num(1.0));
        $eqValue = (new EvaluateOutput())->setValue(Value::num(0.0));

        switch (true) {
            case $l->getType() === Type::NUM && $r->getType() === Type::NUM:
                if ($l->getNum() < $r->getNum()) {
                    return $ltValue;
                }
                if ($l->getNum() > $r->getNum()) {
                    return $gtValue;
                }
                return $eqValue;
            case $l->getType() === Type::BOOL && $r->getType() === Type::BOOL:
                if (!$l->getBool() && $r->getBool()) {
                    return $ltValue;
                }
                if ($l->getBool() && !$r->getBool()) {
                    return $gtValue;
                }
                return $eqValue;
            case $l->getType() === Type::STR && $r->getType() === Type::STR:
                if ($l->getStr() < $r->getStr()) {
                    return $ltValue;
                }
                if ($l->getStr() > $r->getStr()) {
                    return $gtValue;
                }
                return $eqValue;
            case $l->getType() === Type::ARR && $r->getType() === Type::ARR:
                $n = min(count($l->getArr()), count($r->getArr()));
                for ($i = 0; $i < $n; $i++) {
                    $cmp = Evaluator::compare($path, $l->getArr()[$i], $r->getArr()[$i]);
                    if ($cmp->getStatus() !== EvaluateOutput\Status::OK) {
                        return $cmp;
                    }
                    if ($cmp->getValue()->getNum() !== 0.0) {
                        return $cmp;
                    }
                }
                if (count($l->getArr()) < count($r->getArr())) {
                    return $ltValue;
                }
                if (count($l->getArr()) > count($r->getArr())) {
                    return $gtValue;
                }
                return $eqValue;
            default:
                return Evaluator::errorNotComparable($path);
        }
    }

    private static function computeDiv(float $dividend, float $divisor): float
    {
        if (function_exists('fdiv')) {
            return fdiv($dividend, $divisor);
        } else {
            return @($dividend / $divisor);
        }
    }
}
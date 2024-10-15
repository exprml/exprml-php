<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * OpUnary is an OpUnary expression.
 *
 * Generated from protobuf message <code>exprml.v1.OpUnary</code>
 */
class OpUnary extends \Google\Protobuf\Internal\Message
{
    /**
     * Op is the operator.
     *
     * Generated from protobuf field <code>.exprml.v1.OpUnary.Op op = 1 [json_name = "op"];</code>
     */
    protected $op = 0;
    /**
     * Operand is the operand.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr operand = 2 [json_name = "operand"];</code>
     */
    protected $operand = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $op
     *           Op is the operator.
     *     @type \Exprml\PB\Exprml\V1\Expr $operand
     *           Operand is the operand.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PB\Exprml\V1\GPBMetadata\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Op is the operator.
     *
     * Generated from protobuf field <code>.exprml.v1.OpUnary.Op op = 1 [json_name = "op"];</code>
     * @return int
     */
    public function getOp()
    {
        return $this->op;
    }

    /**
     * Op is the operator.
     *
     * Generated from protobuf field <code>.exprml.v1.OpUnary.Op op = 1 [json_name = "op"];</code>
     * @param int $var
     * @return $this
     */
    public function setOp($var)
    {
        GPBUtil::checkEnum($var, \Exprml\PB\Exprml\V1\OpUnary\Op::class);
        $this->op = $var;

        return $this;
    }

    /**
     * Operand is the operand.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr operand = 2 [json_name = "operand"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getOperand()
    {
        return $this->operand;
    }

    public function hasOperand()
    {
        return isset($this->operand);
    }

    public function clearOperand()
    {
        unset($this->operand);
    }

    /**
     * Operand is the operand.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr operand = 2 [json_name = "operand"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setOperand($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->operand = $var;

        return $this;
    }

}

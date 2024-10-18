<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * OpVariadic is an OpVariadic expression.
 *
 * Generated from protobuf message <code>exprml.v1.OpVariadic</code>
 */
class OpVariadic extends \Google\Protobuf\Internal\Message
{
    /**
     * Op is the operator.
     *
     * Generated from protobuf field <code>.exprml.v1.OpVariadic.Op op = 1 [json_name = "op"];</code>
     */
    protected $op = 0;
    /**
     * Operands is the list of operands.
     *
     * Generated from protobuf field <code>repeated .exprml.v1.Expr operands = 2 [json_name = "operands"];</code>
     */
    private $operands;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $op
     *           Op is the operator.
     *     @type array<\Exprml\PB\Exprml\V1\Expr>|\Google\Protobuf\Internal\RepeatedField $operands
     *           Operands is the list of operands.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PBMetadata\Exprml\V1\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Op is the operator.
     *
     * Generated from protobuf field <code>.exprml.v1.OpVariadic.Op op = 1 [json_name = "op"];</code>
     * @return int
     */
    public function getOp()
    {
        return $this->op;
    }

    /**
     * Op is the operator.
     *
     * Generated from protobuf field <code>.exprml.v1.OpVariadic.Op op = 1 [json_name = "op"];</code>
     * @param int $var
     * @return $this
     */
    public function setOp($var)
    {
        GPBUtil::checkEnum($var, \Exprml\PB\Exprml\V1\OpVariadic\Op::class);
        $this->op = $var;

        return $this;
    }

    /**
     * Operands is the list of operands.
     *
     * Generated from protobuf field <code>repeated .exprml.v1.Expr operands = 2 [json_name = "operands"];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getOperands()
    {
        return $this->operands;
    }

    /**
     * Operands is the list of operands.
     *
     * Generated from protobuf field <code>repeated .exprml.v1.Expr operands = 2 [json_name = "operands"];</code>
     * @param array<\Exprml\PB\Exprml\V1\Expr>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setOperands($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Exprml\PB\Exprml\V1\Expr::class);
        $this->operands = $arr;

        return $this;
    }

}


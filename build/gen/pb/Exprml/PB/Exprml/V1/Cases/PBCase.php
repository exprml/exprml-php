<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1\Cases;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Case is a conditional branch.
 *
 * Generated from protobuf message <code>exprml.v1.Cases.Case</code>
 */
class PBCase extends \Google\Protobuf\Internal\Message
{
    /**
     * Whether the case is otherwise.
     *
     * Generated from protobuf field <code>bool is_otherwise = 1 [json_name = "isOtherwise"];</code>
     */
    protected $is_otherwise = false;
    /**
     * When is the condition of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr when = 2 [json_name = "when"];</code>
     */
    protected $when = null;
    /**
     * Then is the body of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr then = 3 [json_name = "then"];</code>
     */
    protected $then = null;
    /**
     * Otherwise is the body of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr otherwise = 4 [json_name = "otherwise"];</code>
     */
    protected $otherwise = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type bool $is_otherwise
     *           Whether the case is otherwise.
     *     @type \Exprml\PB\Exprml\V1\Expr $when
     *           When is the condition of the case.
     *     @type \Exprml\PB\Exprml\V1\Expr $then
     *           Then is the body of the case.
     *     @type \Exprml\PB\Exprml\V1\Expr $otherwise
     *           Otherwise is the body of the case.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PBMetadata\Exprml\V1\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Whether the case is otherwise.
     *
     * Generated from protobuf field <code>bool is_otherwise = 1 [json_name = "isOtherwise"];</code>
     * @return bool
     */
    public function getIsOtherwise()
    {
        return $this->is_otherwise;
    }

    /**
     * Whether the case is otherwise.
     *
     * Generated from protobuf field <code>bool is_otherwise = 1 [json_name = "isOtherwise"];</code>
     * @param bool $var
     * @return $this
     */
    public function setIsOtherwise($var)
    {
        GPBUtil::checkBool($var);
        $this->is_otherwise = $var;

        return $this;
    }

    /**
     * When is the condition of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr when = 2 [json_name = "when"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getWhen()
    {
        return $this->when;
    }

    public function hasWhen()
    {
        return isset($this->when);
    }

    public function clearWhen()
    {
        unset($this->when);
    }

    /**
     * When is the condition of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr when = 2 [json_name = "when"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setWhen($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->when = $var;

        return $this;
    }

    /**
     * Then is the body of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr then = 3 [json_name = "then"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getThen()
    {
        return $this->then;
    }

    public function hasThen()
    {
        return isset($this->then);
    }

    public function clearThen()
    {
        unset($this->then);
    }

    /**
     * Then is the body of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr then = 3 [json_name = "then"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setThen($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->then = $var;

        return $this;
    }

    /**
     * Otherwise is the body of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr otherwise = 4 [json_name = "otherwise"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getOtherwise()
    {
        return $this->otherwise;
    }

    public function hasOtherwise()
    {
        return isset($this->otherwise);
    }

    public function clearOtherwise()
    {
        unset($this->otherwise);
    }

    /**
     * Otherwise is the body of the case.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr otherwise = 4 [json_name = "otherwise"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setOtherwise($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->otherwise = $var;

        return $this;
    }

}


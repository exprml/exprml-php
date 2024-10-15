<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Elem is an Elem expression.
 *
 * Generated from protobuf message <code>exprml.v1.Elem</code>
 */
class Elem extends \Google\Protobuf\Internal\Message
{
    /**
     * Get is the expression to get the element.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr get = 1 [json_name = "get"];</code>
     */
    protected $get = null;
    /**
     * From is the index to get the element.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr from = 2 [json_name = "from"];</code>
     */
    protected $from = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Exprml\PB\Exprml\V1\Expr $get
     *           Get is the expression to get the element.
     *     @type \Exprml\PB\Exprml\V1\Expr $from
     *           From is the index to get the element.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PB\Exprml\V1\GPBMetadata\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Get is the expression to get the element.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr get = 1 [json_name = "get"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getGet()
    {
        return $this->get;
    }

    public function hasGet()
    {
        return isset($this->get);
    }

    public function clearGet()
    {
        unset($this->get);
    }

    /**
     * Get is the expression to get the element.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr get = 1 [json_name = "get"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setGet($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->get = $var;

        return $this;
    }

    /**
     * From is the index to get the element.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr from = 2 [json_name = "from"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    public function hasFrom()
    {
        return isset($this->from);
    }

    public function clearFrom()
    {
        unset($this->from);
    }

    /**
     * From is the index to get the element.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr from = 2 [json_name = "from"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setFrom($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->from = $var;

        return $this;
    }

}

<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Scalar is a Scalar expression.
 *
 * Generated from protobuf message <code>exprml.v1.Scalar</code>
 */
class Scalar extends \Google\Protobuf\Internal\Message
{
    /**
     * Scalar is a scalar value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value scalar = 1 [json_name = "scalar"];</code>
     */
    protected $scalar = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Exprml\PB\Exprml\V1\Value $scalar
     *           Scalar is a scalar value.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PBMetadata\Exprml\V1\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Scalar is a scalar value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value scalar = 1 [json_name = "scalar"];</code>
     * @return \Exprml\PB\Exprml\V1\Value|null
     */
    public function getScalar()
    {
        return $this->scalar;
    }

    public function hasScalar()
    {
        return isset($this->scalar);
    }

    public function clearScalar()
    {
        unset($this->scalar);
    }

    /**
     * Scalar is a scalar value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value scalar = 1 [json_name = "scalar"];</code>
     * @param \Exprml\PB\Exprml\V1\Value $var
     * @return $this
     */
    public function setScalar($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Value::class);
        $this->scalar = $var;

        return $this;
    }

}


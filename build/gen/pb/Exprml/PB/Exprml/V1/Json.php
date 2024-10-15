<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Json is a Json expression.
 *
 * Generated from protobuf message <code>exprml.v1.Json</code>
 */
class Json extends \Google\Protobuf\Internal\Message
{
    /**
     * Json is a JSON value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value json = 1 [json_name = "json"];</code>
     */
    protected $json = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Exprml\PB\Exprml\V1\Value $json
     *           Json is a JSON value.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PB\Exprml\V1\GPBMetadata\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Json is a JSON value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value json = 1 [json_name = "json"];</code>
     * @return \Exprml\PB\Exprml\V1\Value|null
     */
    public function getJson()
    {
        return $this->json;
    }

    public function hasJson()
    {
        return isset($this->json);
    }

    public function clearJson()
    {
        unset($this->json);
    }

    /**
     * Json is a JSON value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value json = 1 [json_name = "json"];</code>
     * @param \Exprml\PB\Exprml\V1\Value $var
     * @return $this
     */
    public function setJson($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Value::class);
        $this->json = $var;

        return $this;
    }

}

<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Obj is an Obj expression.
 *
 * Generated from protobuf message <code>exprml.v1.Obj</code>
 */
class Obj extends \Google\Protobuf\Internal\Message
{
    /**
     * Obj is an object.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Expr> obj = 1 [json_name = "obj"];</code>
     */
    private $obj;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type array|\Google\Protobuf\Internal\MapField $obj
     *           Obj is an object.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PBMetadata\Exprml\V1\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Obj is an object.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Expr> obj = 1 [json_name = "obj"];</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getObj()
    {
        return $this->obj;
    }

    /**
     * Obj is an object.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Expr> obj = 1 [json_name = "obj"];</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setObj($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::MESSAGE, \Exprml\PB\Exprml\V1\Expr::class);
        $this->obj = $arr;

        return $this;
    }

}


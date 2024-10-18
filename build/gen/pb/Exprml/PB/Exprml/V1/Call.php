<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Call is a Call expression.
 *
 * Generated from protobuf message <code>exprml.v1.Call</code>
 */
class Call extends \Google\Protobuf\Internal\Message
{
    /**
     * Ident is the identifier of the call.
     *
     * Generated from protobuf field <code>string ident = 1 [json_name = "ident"];</code>
     */
    protected $ident = '';
    /**
     * Args is the list of arguments.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Expr> args = 2 [json_name = "args"];</code>
     */
    private $args;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $ident
     *           Ident is the identifier of the call.
     *     @type array|\Google\Protobuf\Internal\MapField $args
     *           Args is the list of arguments.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PBMetadata\Exprml\V1\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Ident is the identifier of the call.
     *
     * Generated from protobuf field <code>string ident = 1 [json_name = "ident"];</code>
     * @return string
     */
    public function getIdent()
    {
        return $this->ident;
    }

    /**
     * Ident is the identifier of the call.
     *
     * Generated from protobuf field <code>string ident = 1 [json_name = "ident"];</code>
     * @param string $var
     * @return $this
     */
    public function setIdent($var)
    {
        GPBUtil::checkString($var, True);
        $this->ident = $var;

        return $this;
    }

    /**
     * Args is the list of arguments.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Expr> args = 2 [json_name = "args"];</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Args is the list of arguments.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Expr> args = 2 [json_name = "args"];</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setArgs($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::MESSAGE, \Exprml\PB\Exprml\V1\Expr::class);
        $this->args = $arr;

        return $this;
    }

}


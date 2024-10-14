<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\V1\PBEval;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Definition is a function or variable definition.
 *
 * Generated from protobuf message <code>exprml.v1.Eval.Definition</code>
 */
class Definition extends \Google\Protobuf\Internal\Message
{
    /**
     * Ident is the identifier of the definition.
     *
     * Generated from protobuf field <code>string ident = 1 [json_name = "ident"];</code>
     */
    protected $ident = '';
    /**
     * Args is the list of arguments.
     *
     * Generated from protobuf field <code>repeated string args = 2 [json_name = "args"];</code>
     */
    private $args;
    /**
     * Body is the body of the definition.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr body = 3 [json_name = "body"];</code>
     */
    protected $body = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $ident
     *           Ident is the identifier of the definition.
     *     @type array<string>|\Google\Protobuf\Internal\RepeatedField $args
     *           Args is the list of arguments.
     *     @type \Exprml\V1\Expr $body
     *           Body is the body of the definition.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\V1\GPBMetadata\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Ident is the identifier of the definition.
     *
     * Generated from protobuf field <code>string ident = 1 [json_name = "ident"];</code>
     * @return string
     */
    public function getIdent()
    {
        return $this->ident;
    }

    /**
     * Ident is the identifier of the definition.
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
     * Generated from protobuf field <code>repeated string args = 2 [json_name = "args"];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Args is the list of arguments.
     *
     * Generated from protobuf field <code>repeated string args = 2 [json_name = "args"];</code>
     * @param array<string>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setArgs($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->args = $arr;

        return $this;
    }

    /**
     * Body is the body of the definition.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr body = 3 [json_name = "body"];</code>
     * @return \Exprml\V1\Expr|null
     */
    public function getBody()
    {
        return $this->body;
    }

    public function hasBody()
    {
        return isset($this->body);
    }

    public function clearBody()
    {
        unset($this->body);
    }

    /**
     * Body is the body of the definition.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr body = 3 [json_name = "body"];</code>
     * @param \Exprml\V1\Expr $var
     * @return $this
     */
    public function setBody($var)
    {
        GPBUtil::checkMessage($var, \Exprml\V1\Expr::class);
        $this->body = $var;

        return $this;
    }

}


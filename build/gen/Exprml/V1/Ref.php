<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Ref is a Ref expression.
 *
 * Generated from protobuf message <code>exprml.v1.Ref</code>
 */
class Ref extends \Google\Protobuf\Internal\Message
{
    /**
     * Ident is the identifier of the reference.
     *
     * Generated from protobuf field <code>string ident = 1 [json_name = "ident"];</code>
     */
    protected $ident = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $ident
     *           Ident is the identifier of the reference.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\V1\GPBMetadata\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * Ident is the identifier of the reference.
     *
     * Generated from protobuf field <code>string ident = 1 [json_name = "ident"];</code>
     * @return string
     */
    public function getIdent()
    {
        return $this->ident;
    }

    /**
     * Ident is the identifier of the reference.
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

}


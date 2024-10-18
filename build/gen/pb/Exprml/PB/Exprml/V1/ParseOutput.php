<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/parser.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * ParseOutput is the output message for the Parse method.
 *
 * Generated from protobuf message <code>exprml.v1.ParseOutput</code>
 */
class ParseOutput extends \Google\Protobuf\Internal\Message
{
    /**
     * Whether an error occurred during parsing.
     *
     * Generated from protobuf field <code>bool is_error = 1 [json_name = "isError"];</code>
     */
    protected $is_error = false;
    /**
     * Error message if is_error is true.
     *
     * Generated from protobuf field <code>string error_message = 2 [json_name = "errorMessage"];</code>
     */
    protected $error_message = '';
    /**
     * Parsed Expr.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr expr = 3 [json_name = "expr"];</code>
     */
    protected $expr = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type bool $is_error
     *           Whether an error occurred during parsing.
     *     @type string $error_message
     *           Error message if is_error is true.
     *     @type \Exprml\PB\Exprml\V1\Expr $expr
     *           Parsed Expr.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PBMetadata\Exprml\V1\Parser::initOnce();
        parent::__construct($data);
    }

    /**
     * Whether an error occurred during parsing.
     *
     * Generated from protobuf field <code>bool is_error = 1 [json_name = "isError"];</code>
     * @return bool
     */
    public function getIsError()
    {
        return $this->is_error;
    }

    /**
     * Whether an error occurred during parsing.
     *
     * Generated from protobuf field <code>bool is_error = 1 [json_name = "isError"];</code>
     * @param bool $var
     * @return $this
     */
    public function setIsError($var)
    {
        GPBUtil::checkBool($var);
        $this->is_error = $var;

        return $this;
    }

    /**
     * Error message if is_error is true.
     *
     * Generated from protobuf field <code>string error_message = 2 [json_name = "errorMessage"];</code>
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * Error message if is_error is true.
     *
     * Generated from protobuf field <code>string error_message = 2 [json_name = "errorMessage"];</code>
     * @param string $var
     * @return $this
     */
    public function setErrorMessage($var)
    {
        GPBUtil::checkString($var, True);
        $this->error_message = $var;

        return $this;
    }

    /**
     * Parsed Expr.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr expr = 3 [json_name = "expr"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getExpr()
    {
        return $this->expr;
    }

    public function hasExpr()
    {
        return isset($this->expr);
    }

    public function clearExpr()
    {
        unset($this->expr);
    }

    /**
     * Parsed Expr.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr expr = 3 [json_name = "expr"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setExpr($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->expr = $var;

        return $this;
    }

}


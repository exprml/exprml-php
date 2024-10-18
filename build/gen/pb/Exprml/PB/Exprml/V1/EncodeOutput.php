<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/encoder.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * EncodeOutput is the output message for the Encode method.
 *
 * Generated from protobuf message <code>exprml.v1.EncodeOutput</code>
 */
class EncodeOutput extends \Google\Protobuf\Internal\Message
{
    /**
     * Whether an error occurred during encoding.
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
     * Encoded YAML or JSON string.
     *
     * Generated from protobuf field <code>string result = 3 [json_name = "result"];</code>
     */
    protected $result = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type bool $is_error
     *           Whether an error occurred during encoding.
     *     @type string $error_message
     *           Error message if is_error is true.
     *     @type string $result
     *           Encoded YAML or JSON string.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PBMetadata\Exprml\V1\Encoder::initOnce();
        parent::__construct($data);
    }

    /**
     * Whether an error occurred during encoding.
     *
     * Generated from protobuf field <code>bool is_error = 1 [json_name = "isError"];</code>
     * @return bool
     */
    public function getIsError()
    {
        return $this->is_error;
    }

    /**
     * Whether an error occurred during encoding.
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
     * Encoded YAML or JSON string.
     *
     * Generated from protobuf field <code>string result = 3 [json_name = "result"];</code>
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Encoded YAML or JSON string.
     *
     * Generated from protobuf field <code>string result = 3 [json_name = "result"];</code>
     * @param string $var
     * @return $this
     */
    public function setResult($var)
    {
        GPBUtil::checkString($var, True);
        $this->result = $var;

        return $this;
    }

}


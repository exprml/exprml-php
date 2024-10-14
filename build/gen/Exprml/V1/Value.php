<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/value.proto

namespace Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * JSON value.
 *
 * Generated from protobuf message <code>exprml.v1.Value</code>
 */
class Value extends \Google\Protobuf\Internal\Message
{
    /**
     * Type of the value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value.Type type = 1 [json_name = "type"];</code>
     */
    protected $type = 0;
    /**
     * bool has a boolean value if the type is TYPE_BOOL.
     *
     * Generated from protobuf field <code>bool bool = 2 [json_name = "bool"];</code>
     */
    protected $bool = false;
    /**
     * num has a number value if the type is TYPE_NUM.
     *
     * Generated from protobuf field <code>double num = 3 [json_name = "num"];</code>
     */
    protected $num = 0.0;
    /**
     * str has a string value if the type is TYPE_STR.
     *
     * Generated from protobuf field <code>string str = 4 [json_name = "str"];</code>
     */
    protected $str = '';
    /**
     * arr has an array value if the type is TYPE_ARR.
     *
     * Generated from protobuf field <code>repeated .exprml.v1.Value arr = 5 [json_name = "arr"];</code>
     */
    private $arr;
    /**
     * obj has an object value if the type is TYPE_OBJ.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Value> obj = 6 [json_name = "obj"];</code>
     */
    private $obj;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $type
     *           Type of the value.
     *     @type bool $bool
     *           bool has a boolean value if the type is TYPE_BOOL.
     *     @type float $num
     *           num has a number value if the type is TYPE_NUM.
     *     @type string $str
     *           str has a string value if the type is TYPE_STR.
     *     @type array<\Exprml\V1\Value>|\Google\Protobuf\Internal\RepeatedField $arr
     *           arr has an array value if the type is TYPE_ARR.
     *     @type array|\Google\Protobuf\Internal\MapField $obj
     *           obj has an object value if the type is TYPE_OBJ.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\V1\GPBMetadata\Value::initOnce();
        parent::__construct($data);
    }

    /**
     * Type of the value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value.Type type = 1 [json_name = "type"];</code>
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Type of the value.
     *
     * Generated from protobuf field <code>.exprml.v1.Value.Type type = 1 [json_name = "type"];</code>
     * @param int $var
     * @return $this
     */
    public function setType($var)
    {
        GPBUtil::checkEnum($var, \Exprml\V1\Value\Type::class);
        $this->type = $var;

        return $this;
    }

    /**
     * bool has a boolean value if the type is TYPE_BOOL.
     *
     * Generated from protobuf field <code>bool bool = 2 [json_name = "bool"];</code>
     * @return bool
     */
    public function getBool()
    {
        return $this->bool;
    }

    /**
     * bool has a boolean value if the type is TYPE_BOOL.
     *
     * Generated from protobuf field <code>bool bool = 2 [json_name = "bool"];</code>
     * @param bool $var
     * @return $this
     */
    public function setBool($var)
    {
        GPBUtil::checkBool($var);
        $this->bool = $var;

        return $this;
    }

    /**
     * num has a number value if the type is TYPE_NUM.
     *
     * Generated from protobuf field <code>double num = 3 [json_name = "num"];</code>
     * @return float
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * num has a number value if the type is TYPE_NUM.
     *
     * Generated from protobuf field <code>double num = 3 [json_name = "num"];</code>
     * @param float $var
     * @return $this
     */
    public function setNum($var)
    {
        GPBUtil::checkDouble($var);
        $this->num = $var;

        return $this;
    }

    /**
     * str has a string value if the type is TYPE_STR.
     *
     * Generated from protobuf field <code>string str = 4 [json_name = "str"];</code>
     * @return string
     */
    public function getStr()
    {
        return $this->str;
    }

    /**
     * str has a string value if the type is TYPE_STR.
     *
     * Generated from protobuf field <code>string str = 4 [json_name = "str"];</code>
     * @param string $var
     * @return $this
     */
    public function setStr($var)
    {
        GPBUtil::checkString($var, True);
        $this->str = $var;

        return $this;
    }

    /**
     * arr has an array value if the type is TYPE_ARR.
     *
     * Generated from protobuf field <code>repeated .exprml.v1.Value arr = 5 [json_name = "arr"];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getArr()
    {
        return $this->arr;
    }

    /**
     * arr has an array value if the type is TYPE_ARR.
     *
     * Generated from protobuf field <code>repeated .exprml.v1.Value arr = 5 [json_name = "arr"];</code>
     * @param array<\Exprml\V1\Value>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setArr($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Exprml\V1\Value::class);
        $this->arr = $arr;

        return $this;
    }

    /**
     * obj has an object value if the type is TYPE_OBJ.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Value> obj = 6 [json_name = "obj"];</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getObj()
    {
        return $this->obj;
    }

    /**
     * obj has an object value if the type is TYPE_OBJ.
     *
     * Generated from protobuf field <code>map<string, .exprml.v1.Value> obj = 6 [json_name = "obj"];</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setObj($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::MESSAGE, \Exprml\V1\Value::class);
        $this->obj = $arr;

        return $this;
    }

}


<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1\OpBinary;

use UnexpectedValueException;

/**
 * Op is a operator.
 *
 * Protobuf type <code>exprml.v1.OpBinary.Op</code>
 */
class Op
{
    /**
     * Unspecified.
     *
     * Generated from protobuf enum <code>UNSPECIFIED = 0;</code>
     */
    const UNSPECIFIED = 0;
    /**
     * Sub operator.
     *
     * Generated from protobuf enum <code>SUB = 1;</code>
     */
    const SUB = 1;
    /**
     * Div operator.
     *
     * Generated from protobuf enum <code>DIV = 2;</code>
     */
    const DIV = 2;
    /**
     * Eq operator.
     *
     * Generated from protobuf enum <code>EQ = 3;</code>
     */
    const EQ = 3;
    /**
     * Neq operator.
     *
     * Generated from protobuf enum <code>NEQ = 4;</code>
     */
    const NEQ = 4;
    /**
     * Lt operator.
     *
     * Generated from protobuf enum <code>LT = 5;</code>
     */
    const LT = 5;
    /**
     * Lte operator.
     *
     * Generated from protobuf enum <code>LTE = 6;</code>
     */
    const LTE = 6;
    /**
     * Gt operator.
     *
     * Generated from protobuf enum <code>GT = 7;</code>
     */
    const GT = 7;
    /**
     * Gte operator.
     *
     * Generated from protobuf enum <code>GTE = 8;</code>
     */
    const GTE = 8;

    private static $valueToName = [
        self::UNSPECIFIED => 'UNSPECIFIED',
        self::SUB => 'SUB',
        self::DIV => 'DIV',
        self::EQ => 'EQ',
        self::NEQ => 'NEQ',
        self::LT => 'LT',
        self::LTE => 'LTE',
        self::GT => 'GT',
        self::GTE => 'GTE',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}


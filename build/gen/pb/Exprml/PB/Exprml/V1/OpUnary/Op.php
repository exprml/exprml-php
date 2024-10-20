<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1\OpUnary;

use UnexpectedValueException;

/**
 * Op is a operator.
 *
 * Protobuf type <code>exprml.v1.OpUnary.Op</code>
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
     * Len operator.
     *
     * Generated from protobuf enum <code>LEN = 1;</code>
     */
    const LEN = 1;
    /**
     * Not operator.
     *
     * Generated from protobuf enum <code>NOT = 2;</code>
     */
    const NOT = 2;
    /**
     * Flat operator.
     *
     * Generated from protobuf enum <code>FLAT = 3;</code>
     */
    const FLAT = 3;
    /**
     * Floor operator.
     *
     * Generated from protobuf enum <code>FLOOR = 4;</code>
     */
    const FLOOR = 4;
    /**
     * Ceil operator.
     *
     * Generated from protobuf enum <code>CEIL = 5;</code>
     */
    const CEIL = 5;
    /**
     * Abort operator.
     *
     * Generated from protobuf enum <code>ABORT = 6;</code>
     */
    const ABORT = 6;

    private static $valueToName = [
        self::UNSPECIFIED => 'UNSPECIFIED',
        self::LEN => 'LEN',
        self::NOT => 'NOT',
        self::FLAT => 'FLAT',
        self::FLOOR => 'FLOOR',
        self::CEIL => 'CEIL',
        self::ABORT => 'ABORT',
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


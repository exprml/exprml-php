<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/value.proto

namespace Exprml\V1\GPBMetadata;

class Value
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            "\x0A\x99\x04\x0A\x15exprml/v1/value.proto\x12\x09exprml.v1\"\xD6\x02\x0A\x05Value\x12)\x0A\x04type\x18\x01 \x01(\x0E2\x15.exprml.v1.Value.TypeR\x04type\x12\x12\x0A\x04bool\x18\x02 \x01(\x08R\x04bool\x12\x10\x0A\x03num\x18\x03 \x01(\x01R\x03num\x12\x10\x0A\x03str\x18\x04 \x01(\x09R\x03str\x12\"\x0A\x03arr\x18\x05 \x03(\x0B2\x10.exprml.v1.ValueR\x03arr\x12+\x0A\x03obj\x18\x06 \x03(\x0B2\x19.exprml.v1.Value.ObjEntryR\x03obj\x1AH\x0A\x08ObjEntry\x12\x10\x0A\x03key\x18\x01 \x01(\x09R\x03key\x12&\x0A\x05value\x18\x02 \x01(\x0B2\x10.exprml.v1.ValueR\x05value:\x028\x01\"O\x0A\x04Type\x12\x0F\x0A\x0BUNSPECIFIED\x10\x00\x12\x08\x0A\x04NULL\x10\x01\x12\x08\x0A\x04BOOL\x10\x02\x12\x07\x0A\x03NUM\x10\x03\x12\x07\x0A\x03STR\x10\x04\x12\x07\x0A\x03ARR\x10\x05\x12\x07\x0A\x03OBJ\x10\x06B\x93\x01\x0A\x0Dcom.exprml.v1B\x0AValueProtoP\x01Z1github.com/exprml/exprml-go/pb/exprml/v1;exprmlv1\xA2\x02\x03EXX\xAA\x02\x09Exprml.V1\xCA\x02\x09Exprml\\V1\xE2\x02\x15Exprml\\V1\\GPBMetadata\xEA\x02\x0AExprml::V1b\x06proto3"
        , true);

        static::$is_initialized = true;
    }
}


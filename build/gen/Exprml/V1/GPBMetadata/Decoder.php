<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/decoder.proto

namespace Exprml\V1\GPBMetadata;

class Decoder
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \Exprml\V1\GPBMetadata\Value::initOnce();
        $pool->internalAddGeneratedFile(
            "\x0A\xA7\x03\x0A\x17exprml/v1/decoder.proto\x12\x09exprml.v1\"!\x0A\x0BDecodeInput\x12\x12\x0A\x04yaml\x18\x01 \x01(\x09R\x04yaml\"v\x0A\x0CDecodeOutput\x12\x19\x0A\x08is_error\x18\x01 \x01(\x08R\x07isError\x12#\x0A\x0Derror_message\x18\x02 \x01(\x09R\x0CerrorMessage\x12&\x0A\x05value\x18\x03 \x01(\x0B2\x10.exprml.v1.ValueR\x05value2F\x0A\x07Decoder\x12;\x0A\x06Decode\x12\x16.exprml.v1.DecodeInput\x1A\x17.exprml.v1.DecodeOutput\"\x00B\x95\x01\x0A\x0Dcom.exprml.v1B\x0CDecoderProtoP\x01Z1github.com/exprml/exprml-go/pb/exprml/v1;exprmlv1\xA2\x02\x03EXX\xAA\x02\x09Exprml.V1\xCA\x02\x09Exprml\\V1\xE2\x02\x15Exprml\\V1\\GPBMetadata\xEA\x02\x0AExprml::V1b\x06proto3"
        , true);

        static::$is_initialized = true;
    }
}


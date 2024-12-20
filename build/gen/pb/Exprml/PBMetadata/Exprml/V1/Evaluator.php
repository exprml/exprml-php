<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/evaluator.proto

namespace Exprml\PBMetadata\Exprml\V1;

class Evaluator
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \Exprml\PBMetadata\Exprml\V1\Expr::initOnce();
        \Exprml\PBMetadata\Exprml\V1\Value::initOnce();
        $pool->internalAddGeneratedFile(
            "\x0A\x8A\x07\x0A\x19exprml/v1/evaluator.proto\x12\x09exprml.v1\x1A\x15exprml/v1/value.proto\"e\x0A\x08DefStack\x12+\x0A\x06parent\x18\x01 \x01(\x0B2\x13.exprml.v1.DefStackR\x06parent\x12,\x0A\x03def\x18\x02 \x01(\x0B2\x1A.exprml.v1.Eval.DefinitionR\x03def\"f\x0A\x0DEvaluateInput\x120\x0A\x09def_stack\x18\x01 \x01(\x0B2\x13.exprml.v1.DefStackR\x08defStack\x12#\x0A\x04expr\x18\x02 \x01(\x0B2\x0F.exprml.v1.ExprR\x04expr\"\xAD\x03\x0A\x0EEvaluateOutput\x128\x0A\x06status\x18\x01 \x01(\x0E2 .exprml.v1.EvaluateOutput.StatusR\x06status\x12#\x0A\x0Derror_message\x18\x02 \x01(\x09R\x0CerrorMessage\x123\x0A\x0Aerror_path\x18\x03 \x01(\x0B2\x14.exprml.v1.Expr.PathR\x09errorPath\x12&\x0A\x05value\x18\x04 \x01(\x0B2\x10.exprml.v1.ValueR\x05value\"\xDE\x01\x0A\x06Status\x12\x06\x0A\x02OK\x10\x00\x12\x11\x0A\x0DINVALID_INDEX\x10d\x12\x0F\x0A\x0BINVALID_KEY\x10e\x12\x13\x0A\x0FUNEXPECTED_TYPE\x10f\x12\x15\x0A\x11ARGUMENT_MISMATCH\x10g\x12\x18\x0A\x14CASES_NOT_EXHAUSTIVE\x10h\x12\x17\x0A\x13REFERENCE_NOT_FOUND\x10i\x12\x12\x0A\x0ENOT_COMPARABLE\x10j\x12\x15\x0A\x11NOT_FINITE_NUMBER\x10k\x12\x0B\x0A\x07ABORTED\x10l\x12\x11\x0A\x0DUNKNOWN_ERROR\x10m2N\x0A\x09Evaluator\x12A\x0A\x08Evaluate\x12\x18.exprml.v1.EvaluateInput\x1A\x19.exprml.v1.EvaluateOutput\"\x00Bt\x0A\x0Dcom.exprml.v1B\x0EEvaluatorProtoP\x01\xA2\x02\x03EXX\xAA\x02\x09Exprml.V1\xCA\x02\x13Exprml\\PB\\Exprml\\V1\xE2\x02\x1BExprml\\PBMetadata\\Exprml\\V1\xEA\x02\x0AExprml::V1b\x06proto3"
        , true);

        static::$is_initialized = true;
    }
}


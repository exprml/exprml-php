<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PBMetadata\Exprml\V1;

class Expr
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \Exprml\PBMetadata\Exprml\V1\Value::initOnce();
        $pool->internalAddGeneratedFile(
            "\x0A\xDC\x15\x0A\x14exprml/v1/expr.proto\x12\x09exprml.v1\"\x9C\x07\x0A\x04Expr\x12(\x0A\x04path\x18\x01 \x01(\x0B2\x14.exprml.v1.Expr.PathR\x04path\x12&\x0A\x05value\x18\x02 \x01(\x0B2\x10.exprml.v1.ValueR\x05value\x12(\x0A\x04kind\x18\x03 \x01(\x0E2\x14.exprml.v1.Expr.KindR\x04kind\x12#\x0A\x04eval\x18\x0A \x01(\x0B2\x0F.exprml.v1.EvalR\x04eval\x12)\x0A\x06scalar\x18\x0B \x01(\x0B2\x11.exprml.v1.ScalarR\x06scalar\x12 \x0A\x03ref\x18\x0C \x01(\x0B2\x0E.exprml.v1.RefR\x03ref\x12 \x0A\x03obj\x18\x0D \x01(\x0B2\x0E.exprml.v1.ObjR\x03obj\x12 \x0A\x03arr\x18\x0E \x01(\x0B2\x0E.exprml.v1.ArrR\x03arr\x12#\x0A\x04json\x18\x0F \x01(\x0B2\x0F.exprml.v1.JsonR\x04json\x12#\x0A\x04iter\x18\x10 \x01(\x0B2\x0F.exprml.v1.IterR\x04iter\x12#\x0A\x04elem\x18\x11 \x01(\x0B2\x0F.exprml.v1.ElemR\x04elem\x12#\x0A\x04call\x18\x12 \x01(\x0B2\x0F.exprml.v1.CallR\x04call\x12&\x0A\x05cases\x18\x13 \x01(\x0B2\x10.exprml.v1.CasesR\x05cases\x12-\x0A\x08op_unary\x18\x14 \x01(\x0B2\x12.exprml.v1.OpUnaryR\x07opUnary\x120\x0A\x09op_binary\x18\x15 \x01(\x0B2\x13.exprml.v1.OpBinaryR\x08opBinary\x126\x0A\x0Bop_variadic\x18\x16 \x01(\x0B2\x15.exprml.v1.OpVariadicR\x0AopVariadic\x1Aa\x0A\x04Path\x12*\x0A\x03pos\x18\x01 \x03(\x0B2\x18.exprml.v1.Expr.Path.PosR\x03pos\x1A-\x0A\x03Pos\x12\x14\x0A\x05index\x18\x01 \x01(\x03R\x05index\x12\x10\x0A\x03key\x18\x02 \x01(\x09R\x03key\"\xA9\x01\x0A\x04Kind\x12\x0F\x0A\x0BUNSPECIFIED\x10\x00\x12\x08\x0A\x04EVAL\x10\x0A\x12\x0A\x0A\x06SCALAR\x10\x0B\x12\x07\x0A\x03REF\x10\x0C\x12\x07\x0A\x03OBJ\x10\x0D\x12\x07\x0A\x03ARR\x10\x0E\x12\x08\x0A\x04JSON\x10\x0F\x12\x08\x0A\x04ITER\x10\x10\x12\x08\x0A\x04ELEM\x10\x11\x12\x08\x0A\x04CALL\x10\x12\x12\x09\x0A\x05CASES\x10\x13\x12\x0C\x0A\x08OP_UNARY\x10\x14\x12\x0D\x0A\x09OP_BINARY\x10\x15\x12\x0F\x0A\x0BOP_VARIADIC\x10\x16\"\xBA\x01\x0A\x04Eval\x12#\x0A\x04eval\x18\x01 \x01(\x0B2\x0F.exprml.v1.ExprR\x04eval\x120\x0A\x05where\x18\x02 \x03(\x0B2\x1A.exprml.v1.Eval.DefinitionR\x05where\x1A[\x0A\x0ADefinition\x12\x14\x0A\x05ident\x18\x01 \x01(\x09R\x05ident\x12\x12\x0A\x04args\x18\x02 \x03(\x09R\x04args\x12#\x0A\x04body\x18\x03 \x01(\x0B2\x0F.exprml.v1.ExprR\x04body\"2\x0A\x06Scalar\x12(\x0A\x06scalar\x18\x01 \x01(\x0B2\x10.exprml.v1.ValueR\x06scalar\"\x1B\x0A\x03Ref\x12\x14\x0A\x05ident\x18\x01 \x01(\x09R\x05ident\"y\x0A\x03Obj\x12)\x0A\x03obj\x18\x01 \x03(\x0B2\x17.exprml.v1.Obj.ObjEntryR\x03obj\x1AG\x0A\x08ObjEntry\x12\x10\x0A\x03key\x18\x01 \x01(\x09R\x03key\x12%\x0A\x05value\x18\x02 \x01(\x0B2\x0F.exprml.v1.ExprR\x05value:\x028\x01\"(\x0A\x03Arr\x12!\x0A\x03arr\x18\x01 \x03(\x0B2\x0F.exprml.v1.ExprR\x03arr\",\x0A\x04Json\x12\$\x0A\x04json\x18\x01 \x01(\x0B2\x10.exprml.v1.ValueR\x04json\"\xA7\x01\x0A\x04Iter\x12\x1B\x0A\x09pos_ident\x18\x01 \x01(\x09R\x08posIdent\x12\x1D\x0A\x0Aelem_ident\x18\x02 \x01(\x09R\x09elemIdent\x12!\x0A\x03col\x18\x03 \x01(\x0B2\x0F.exprml.v1.ExprR\x03col\x12\x1F\x0A\x02do\x18\x04 \x01(\x0B2\x0F.exprml.v1.ExprR\x02do\x12\x1F\x0A\x02if\x18\x05 \x01(\x0B2\x0F.exprml.v1.ExprR\x02if\"N\x0A\x04Elem\x12!\x0A\x03get\x18\x01 \x01(\x0B2\x0F.exprml.v1.ExprR\x03get\x12#\x0A\x04from\x18\x02 \x01(\x0B2\x0F.exprml.v1.ExprR\x04from\"\x95\x01\x0A\x04Call\x12\x14\x0A\x05ident\x18\x01 \x01(\x09R\x05ident\x12-\x0A\x04args\x18\x02 \x03(\x0B2\x19.exprml.v1.Call.ArgsEntryR\x04args\x1AH\x0A\x09ArgsEntry\x12\x10\x0A\x03key\x18\x01 \x01(\x09R\x03key\x12%\x0A\x05value\x18\x02 \x01(\x0B2\x0F.exprml.v1.ExprR\x05value:\x028\x01\"\xD9\x01\x0A\x05Cases\x12+\x0A\x05cases\x18\x01 \x03(\x0B2\x15.exprml.v1.Cases.CaseR\x05cases\x1A\xA2\x01\x0A\x04Case\x12!\x0A\x0Cis_otherwise\x18\x01 \x01(\x08R\x0BisOtherwise\x12#\x0A\x04when\x18\x02 \x01(\x0B2\x0F.exprml.v1.ExprR\x04when\x12#\x0A\x04then\x18\x03 \x01(\x0B2\x0F.exprml.v1.ExprR\x04then\x12-\x0A\x09otherwise\x18\x04 \x01(\x0B2\x0F.exprml.v1.ExprR\x09otherwise\"\xAE\x01\x0A\x07OpUnary\x12%\x0A\x02op\x18\x01 \x01(\x0E2\x15.exprml.v1.OpUnary.OpR\x02op\x12)\x0A\x07operand\x18\x02 \x01(\x0B2\x0F.exprml.v1.ExprR\x07operand\"Q\x0A\x02Op\x12\x0F\x0A\x0BUNSPECIFIED\x10\x00\x12\x07\x0A\x03LEN\x10\x01\x12\x07\x0A\x03NOT\x10\x02\x12\x08\x0A\x04FLAT\x10\x03\x12\x09\x0A\x05FLOOR\x10\x04\x12\x08\x0A\x04CEIL\x10\x05\x12\x09\x0A\x05ABORT\x10\x06\"\xDA\x01\x0A\x08OpBinary\x12&\x0A\x02op\x18\x01 \x01(\x0E2\x16.exprml.v1.OpBinary.OpR\x02op\x12#\x0A\x04left\x18\x02 \x01(\x0B2\x0F.exprml.v1.ExprR\x04left\x12%\x0A\x05right\x18\x03 \x01(\x0B2\x0F.exprml.v1.ExprR\x05right\"Z\x0A\x02Op\x12\x0F\x0A\x0BUNSPECIFIED\x10\x00\x12\x07\x0A\x03SUB\x10\x01\x12\x07\x0A\x03DIV\x10\x02\x12\x06\x0A\x02EQ\x10\x03\x12\x07\x0A\x03NEQ\x10\x04\x12\x06\x0A\x02LT\x10\x05\x12\x07\x0A\x03LTE\x10\x06\x12\x06\x0A\x02GT\x10\x07\x12\x07\x0A\x03GTE\x10\x08\"\xC3\x01\x0A\x0AOpVariadic\x12(\x0A\x02op\x18\x01 \x01(\x0E2\x18.exprml.v1.OpVariadic.OpR\x02op\x12+\x0A\x08operands\x18\x02 \x03(\x0B2\x0F.exprml.v1.ExprR\x08operands\"^\x0A\x02Op\x12\x0F\x0A\x0BUNSPECIFIED\x10\x00\x12\x07\x0A\x03ADD\x10\x01\x12\x07\x0A\x03MUL\x10\x02\x12\x07\x0A\x03AND\x10\x03\x12\x06\x0A\x02OR\x10\x04\x12\x07\x0A\x03CAT\x10\x05\x12\x07\x0A\x03MIN\x10\x06\x12\x07\x0A\x03MAX\x10\x07\x12\x09\x0A\x05MERGE\x10\x08Bo\x0A\x0Dcom.exprml.v1B\x09ExprProtoP\x01\xA2\x02\x03EXX\xAA\x02\x09Exprml.V1\xCA\x02\x13Exprml\\PB\\Exprml\\V1\xE2\x02\x1BExprml\\PBMetadata\\Exprml\\V1\xEA\x02\x0AExprml::V1b\x06proto3"
        , true);

        static::$is_initialized = true;
    }
}

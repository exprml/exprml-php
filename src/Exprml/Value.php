<?php

namespace Exprml;

use Exprml\PB\Exprml\V1\Value as PBValue;
use Exprml\PB\Exprml\V1\Value\Type as PBType;

class Value
{
    public static function obj(array $obj): PBValue
    {
        return (new PBValue())->setType(PBType::OBJ)->setObj($obj);
    }

    public static function arr(array $arr): PBValue
    {
        return (new PBValue())->setType(PBType::ARR)->setArr($arr);
    }

    public static function str(string $str): PBValue
    {
        return (new PBValue())->setType(PBType::STR)->setStr($str);
    }

    public static function num(float $num): PBValue
    {
        return (new PBValue())->setType(PBType::NUM)->setNum($num);
    }

    public static function bool(bool $b): PBValue
    {
        return (new PBValue())->setType(PBType::BOOL)->setBool($b);
    }

    public static function keys(PBValue $value): array
    {
        if ($value->getType() !== PBType::OBJ) {
            return [];
        }
        $keys = array_keys(iterator_to_array($value->getObj()));
        sort($keys);
        return $keys;
    }
}
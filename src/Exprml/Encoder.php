<?php

namespace Exprml;

use Exception;
use Exprml\PB\Exprml\V1\EncodeInput;
use Exprml\PB\Exprml\V1\EncodeOutput;
use Exprml\PB\Exprml\V1\Value as PBValue;
use Exprml\PB\Exprml\V1\Value\Type as PBType;
use stdClass;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;

class Encoder
{
    public function encode(EncodeInput $input): EncodeOutput
    {
        try {
            $y = Encoder::convertToPHP($input->getValue());
            $r = new EncodeOutput();
            if ($input->getFormat() === EncodeInput\Format::YAML) {
                $flags = SymfonyYaml::DUMP_OBJECT_AS_MAP
                    | SymfonyYaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE
                    | SymfonyYaml::DUMP_MULTI_LINE_LITERAL_BLOCK;
                return $r->setText(SymfonyYaml::dump($y, 10, 2, $flags));
            } else {
                return $r->setText(json_encode($y, JSON_THROW_ON_ERROR));
            }
        } catch (Exception $e) {
            return (new EncodeOutput)
                ->setIsError(true)
                ->setErrorMessage($e->getMessage());
        }
    }

    private static function convertToPHP(PBValue $v)
    {
        switch ($v->getType()) {
            case PBType::NULL:
                return null;
            case PBType::NUM:
                return $v->getNum();
            case PBType::BOOL:
                return $v->getBool();
            case PBType::STR:
                return $v->getStr();
            case PBType::ARR:
                $arr = [];
                foreach ($v->getArr() as $elem) {
                    $arr[] = Encoder::convertToPHP($elem);
                }
                return $arr;
            case PBType::OBJ:
                $obj = new stdClass();
                foreach ($v->getObj() as $key => $val) {
                    $obj->$key = Encoder::convertToPHP($val);
                }
                return $obj;
            default:
                assert(false, new Exception('Invalid type'));
        }
    }
}

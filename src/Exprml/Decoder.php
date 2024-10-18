<?php

namespace Exprml;


use Exception;
use Exprml\PB\Exprml\V1\DecodeInput;
use Exprml\PB\Exprml\V1\DecodeOutput;
use Exprml\PB\Exprml\V1\Value as PBValue;
use Exprml\PB\Exprml\V1\Value\Type as PBType;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;
use function PHPUnit\Framework\assertEquals;

class Decoder
{
    public function decode(DecodeInput $input): DecodeOutput
    {
        try {
            $y = SymfonyYaml::parse($input->getYaml(), SymfonyYaml::PARSE_OBJECT_FOR_MAP);
            return (new DecodeOutput)->setValue(Decoder::convertFromPHP($y));
        } catch (Exception $e) {
            return (new DecodeOutput)
                ->setIsError(true)
                ->setErrorMessage($e->getMessage());
        }
    }

    private static function convertFromPHP(mixed $yaml): PBValue
    {
        $v = new PBValue();
        switch (gettype($yaml)) {
            case 'NULL':
                return $v->setType(PBType::NULL);
            case 'integer':
            case 'double':
                return $v->setType(PBType::NUM)->setNum((float)$yaml);
            case 'boolean':
                return $v->setType(PBType::BOOL)->setBool((bool)$yaml);
            case 'string':
                return $v->setType(PBType::STR)->setStr((string)$yaml);
            case 'array':
                $arr = [];
                foreach ($yaml as $elem) {
                    $arr[] = Decoder::convertFromPHP($elem);
                }
                return $v->setType(PBType::ARR)->setArr($arr);
            case 'object':
                $obj = [];
                foreach ($yaml as $key => $val) {
                    $obj[$key] = Decoder::convertFromPHP($val);
                }
                return $v->setType(PBType::OBJ)->setObj($obj);
            default:
                assert(false, new Exception('Unsupported type: ' . gettype($yaml)));
        }
    }
}
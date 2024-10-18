<?php

namespace Exprml;

use Exprml\PB\Exprml\V1\DefStack as PBDefStack;
use Exprml\PB\Exprml\V1\Expr;
use Exprml\PB\Exprml\V1\Expr\Path;
use Exprml\PB\Exprml\V1\Json;
use Exprml\PB\Exprml\V1\PBEval\Definition;
use Exprml\PB\Exprml\V1\Value;

class DefStack
{
    public static function register(PBDefStack|null $defStack, Definition $def): PBDefStack
    {
        return (new PBDefStack())
            ->setParent($defStack)
            ->setDef($def);
    }

    public static function find(PBDefStack|null $defStack, string $ident): PBDefStack|null
    {
        if ($defStack === null || $defStack->getDef() === null) {
            return null;
        }
        if ($defStack->getDef()->getIdent() === $ident) {
            return $defStack;
        }
        return DefStack::find($defStack->getParent(), $ident);
    }

    public static function newDefinition(Path $path, string $ident, Value $value): Definition
    {
        return (new Definition())
            ->setIdent($ident)
            ->setBody(
                (new Expr())
                    ->setPath($path)
                    ->setKind(Expr\Kind::JSON)
                    ->setValue($value)
                    ->setJson((new Json())->setJson($value))
            );
    }

}
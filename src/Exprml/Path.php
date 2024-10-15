<?php

namespace Exprml;

use Exprml\PB\Exprml\V1\Expr\Path as PBPath;
use Exprml\PB\Exprml\V1\Expr\Path\Pos;

class Path
{
    /**
     * @param PBPath $path
     * @param (string|int) ...$pos
     * @return PBPath
     */
    public static function append(PBPath $path, string|int ...$pos): PBPath
    {
        return (new PBPath)
            ->setPos(array_merge(iterator_to_array($path->getPos()), $pos));
    }

    public static function format(PBPath $path): string
    {
        if ($path->getPos()->count() === 0) {
            return "/";
        }
        $out = "";
        foreach ($path->getPos() as /** @var Pos $pos */ $pos) {
            if ($pos->getKey() !== "") {
                $out .= "/" . $pos->getKey();
            } else {
                $out .= "/" . $pos->getIndex();
            }
        }
        return $out;
    }
}
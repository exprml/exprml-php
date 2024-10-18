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
    public static function append(PBPath $path, ...$pos): PBPath
    {
        $arr = iterator_to_array($path->getPos());
        foreach ($pos as $p) {
            if (is_int($p)) {
                $arr[] = (new Pos)->setIndex($p);
            } else {
                $arr[] = (new Pos)->setKey($p);
            }
        }
        return (new PBPath)->setPos($arr);
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
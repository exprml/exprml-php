<?php

namespace Tests;


use Exprml\PB\Exprml\V1\Value;

class EvaluatorTestcase
{
    public string $inputYaml = "";
    public Value|null $wantValue = null;
    public bool|null $wantError = null;

}
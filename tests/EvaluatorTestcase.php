<?php

namespace Tests;


use Exprml\PB\Exprml\V1\Value;

class EvaluatorTestcase
{
    public string $inputYaml = "";
    public ?Value $wantValue = null;
    public ?bool $wantError = null;

}
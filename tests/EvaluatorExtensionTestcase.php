<?php

namespace Tests;


use Exprml\PB\Exprml\V1\Value;

class EvaluatorExtensionTestcase
{
    public string $inputYaml = "";
    public Value $wantValue;

    public function __construct(string $inputYaml, Value $wantValue)
    {
        $this->inputYaml = $inputYaml;
        $this->wantValue = $wantValue;
    }
}
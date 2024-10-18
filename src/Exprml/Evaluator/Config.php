<?php

namespace Exprml\Evaluator;

use Exprml\PB\Exprml\V1\EvaluateInput;
use Exprml\PB\Exprml\V1\EvaluateOutput;

class Config
{

    private array|null $extension = null;
    private $beforeEvaluate = null;
    private $afterEvaluate = null;

    public function __construct()
    {
    }

    public function getExtension(): array
    {
        if ($this->extension != null) {
            return $this->extension;
        }
        return [];
    }

    /**
     * $extension is a map of extension name to extension function.
     * Each name must be a string that matches the pattern '^\$[_a-zA-Z][_a-zA-Z0-9]*$'.
     * Each function must have the signature `function (Path $path, array $args): EvaluateOutput`, where $args is an associated array that maps string keys to Value argument values.
     * The string keys must be match '^\$[_a-zA-Z][_a-zA-Z0-9]*$'.
     * @param $extension array<string, callable>
     */
    public function setExtension(array $extension): Config
    {
        $this->extension = $extension;
        return $this;
    }

    public function getBeforeEvaluate(): callable
    {
        if ($this->beforeEvaluate != null) {
            return $this->beforeEvaluate;
        }
        return function (EvaluateInput $input): void {
        };
    }

    /**
     * $beforeEvaluate is a function that is called before the evaluation of an expression.
     * It has the signature `function (EvaluateInput $input): void`.
     * @param callable $beforeEvaluate callable
     */
    public function setBeforeEvaluate(callable $beforeEvaluate): Config
    {
        $this->beforeEvaluate = $beforeEvaluate;
        return $this;
    }

    public function getAfterEvaluate(): callable
    {
        if ($this->afterEvaluate != null) {
            return $this->afterEvaluate;
        }
        return function (EvaluateInput $input, EvaluateOutput $output): void {
        };
    }

    /**
     * $afterEvaluate is a function that is called after the evaluation of an expression.
     * It has the signature `function (EvaluateInput $input, EvaluateOutput $output): void`.
     * @param callable $afterEvaluate callable
     */
    public function setAfterEvaluate(callable $afterEvaluate): Config
    {
        $this->afterEvaluate = $afterEvaluate;
        return $this;
    }
}
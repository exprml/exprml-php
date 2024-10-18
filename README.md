# exprml-php

`exprml-php` is a PHP library implementing an ExprML interpreter.
The ExprML is a programming language that can evaluate expressions represented in the YAML format.

The language specification is available at https://github.com/exprml/exprml-language .

## Installation

```bash
composer require exprml/exprml-php
composer dump-autoload
```

## Example

### Evaluate an expression

```php
<?php

use Exprml\Decoder;
use Exprml\Encoder;
use Exprml\Evaluator;
use Exprml\Evaluator\Config;
use Exprml\Parser;
use Exprml\PB\Exprml\V1\DecodeInput;
use Exprml\PB\Exprml\V1\EncodeInput;
use Exprml\PB\Exprml\V1\EvaluateInput;
use Exprml\PB\Exprml\V1\ParseInput;

require 'vendor/autoload.php';

// Decode the input source code.
$decodeResult = (new Decoder())->decode(
    (new DecodeInput())->setYaml("cat: ['`Hello`', '`, `', '`ExprML`', '`!`']")
);

// Parse AST from the decoded value.
$parseResult = (new Parser())->parse(
    (new ParseInput())->setValue($decodeResult->getValue())
);

// Evaluate the parsed AST to get the result value.
$evaluator = new Evaluator(new Config());
$evaluateResult = $evaluator->evaluateExpr(
    (new EvaluateInput())->setExpr($parseResult->getExpr())
);

// Encode the evaluated result to get the final output.
$encodeResult = (new Encoder())->encode(
    (new EncodeInput())->setValue($evaluateResult->getValue())
);

printf($encodeResult->getResult() . "\n");
// => Hello, ExprML!
```

### Call PHP functions from ExprML

```php
<?php

use Exprml\Decoder;
use Exprml\Encoder;
use Exprml\Evaluator;
use Exprml\Evaluator\Config;
use Exprml\Parser;
use Exprml\PB\Exprml\V1\DecodeInput;
use Exprml\PB\Exprml\V1\EncodeInput;
use Exprml\PB\Exprml\V1\EvaluateInput;
use Exprml\PB\Exprml\V1\EvaluateOutput;
use Exprml\PB\Exprml\V1\Expr\Path;
use Exprml\PB\Exprml\V1\ParseInput;
use Exprml\PB\Exprml\V1\Value;

require 'vendor/autoload.php';

$decodeResult = (new Decoder())
    ->decode((new DecodeInput())->setYaml('$ext_func: { $arg: "`ExprML`" }'));
$parseResult = (new Parser())
    ->parse((new ParseInput())->setValue($decodeResult->getValue()));

$config = (new Config())->setExtension([
    // Define an extension function named $hello, which takes an argument $name and returns a greeting string.
    '$ext_func' => function (Path $path, array $args) {
        /** @var Value $arg */
        $arg = $args['$arg'];
        $ret = (new Value())
            ->setType(Value\Type::STR)
            ->setStr('Calling: $ext_func($arg=' . $arg->getStr() . ')');
        return (new EvaluateOutput())->setValue($ret);
    },
]);

$evaluateResult = (new Evaluator($config))
    ->evaluateExpr((new EvaluateInput())->setExpr($parseResult->getExpr()));
$encodeResult = (new Encoder())
    ->encode((new EncodeInput())->setValue($evaluateResult->getValue()));
printf($encodeResult->getResult() . "\n");
// => 'Calling: $ext_func($arg=ExprML)'
```

### Hook PHP functions before and after each evaluation of nested expressions

```php
<?php

use Exprml\Decoder;
use Exprml\Evaluator;
use Exprml\Evaluator\Config;
use Exprml\Parser;
use Exprml\Path;
use Exprml\PB\Exprml\V1\DecodeInput;
use Exprml\PB\Exprml\V1\EvaluateInput;
use Exprml\PB\Exprml\V1\EvaluateOutput;
use Exprml\PB\Exprml\V1\ParseInput;
use Exprml\PB\Exprml\V1\Value;

require 'vendor/autoload.php';

$decodeResult = (new Decoder())
    ->decode((new DecodeInput())->setYaml("cat: ['`Hello`', '`, `', '`ExprML`', '`!`']"));
$parseResult = (new Parser())
    ->parse((new ParseInput())->setValue($decodeResult->getValue()));

$config = (new Config())
    ->setBeforeEvaluate(
        /* Hook a function before the evaluation of each expression. */
        function (EvaluateInput $input) {
            printf("before:\t%s\n", Path::format($input->getExpr()->getPath()));
        })
    ->setAfterEvaluate(
        /* Hook a function after the evaluation of each expression. */
        function (EvaluateInput $input, EvaluateOutput $output) {
            printf("after:\t%s --> %s\n",
                Path::format($input->getExpr()->getPath()),
                Value\Type::name($output->getValue()->getType()));
        });

(new Evaluator($config))
    ->evaluateExpr((new EvaluateInput())->setExpr($parseResult->getExpr()));
# =>
# before: /
# before: /cat/0
# after:  /cat/0 --> STR
# before: /cat/1
# after:  /cat/1 --> STR
# before: /cat/2
# after:  /cat/2 --> STR
# before: /cat/3
# after:  /cat/3 --> STR
# after:  / --> STR
```

## PHP API documentation

https://exprml.github.io/exprml-php/docs/api/

## Packagist

https://packagist.org/packages/exprml/exprml-php

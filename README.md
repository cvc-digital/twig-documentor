# Twig Documentor

Automatically generates documentation for Twig extensions.

## Installation

```
composer require --dev cvc/twig-documentor
```

## Usage

```php
<?php

$environment = new \Twig\Environment(new \Twig\Loader\ArrayLoader());
$environmentDescriber = new \Cvc\TwigDocumentor\Describer\EnvironmentDescriber(
    new \Cvc\TwigDocumentor\Describer\FunctionDescriber(\phpDocumentor\Reflection\DocBlockFactory::createInstance()),
    new \Cvc\TwigDocumentor\Describer\FilterDescriber(\phpDocumentor\Reflection\DocBlockFactory::createInstance())
);
$documentation = $environmentDescriber->describe($environment);
$documentation = $documentation->withSource(__DIR__.'/*');
```

## Development Team

<table>
    <tr>
        <td align="center" valign="top">
            <img width="150" height="150" src="https://github.com/markuspoerschke.png?s=150">
            <br>
            <a href="https://github.com/markuspoerschke">Markus Poerschke</a>
            <p>Developer</p>
        </td>
    </tr>
</table>

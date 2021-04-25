<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    $parameters->set(
        Option::PATHS,
        [
            __DIR__ . '/app',
        ]
    );

    $parameters->set(
        Option::SKIP,
        [
            __DIR__ . '/app/Console/Kernel.php',
            __DIR__ . '/app/Exceptions/',
            __DIR__ . '/app/Http/Middleware/',
            __DIR__ . '/app/Providers/',
            \Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector::class,
        ]
    );

    // Define what rule sets will be applied
    $parameters->set(Option::SETS, [
        SetList::DEAD_CODE,
    ]);

    $parameters->set(Option::AUTO_IMPORT_NAMES, false);

    $parameters->set(
        Option::AUTOLOAD_PATHS,
        [
            __DIR__ . '/app',
        ]
    );
};

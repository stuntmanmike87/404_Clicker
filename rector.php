<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src'
    ]);
    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    //$rectorConfig->rule(TypedPropertyRector::class);

    // define sets of rules
    //    $rectorConfig->sets([
    //        LevelSetList::UP_TO_PHP_72
    //    ]);

    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::PHP_81,
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_80
    ]);

    $rectorConfig->sets([
        //SymfonySetList::SYMFONY_54,//SYMFONY_60,//SYMFONY_61,
        //SymfonySetList::SYMFONY_CODE_QUALITY,
        //SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);
};

return static function (ContainerConfigurator $containerConfigurator): void {
    //$containerConfigurator->import(SymfonySetList::SYMFONY_54);//SYMFONY_60);//SYMFONY_61);
};

/**
 * @see \Rector\Config\RectorConfig::symfonyContainerXml()
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(
        Option::class,
        __DIR__ . '/var/cache/dev/AppKernelDevDebugContainer.xml'
    );
};

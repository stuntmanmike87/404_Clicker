<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

/**
 * @see \Rector\Config\RectorConfig::symfonyContainerXml()
 */
return static function (RectorConfig $rectorConfig): void {

    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);

    //$rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    // basic rules
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();

    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        //SetList::DEAD_CODE,
        // SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION,
        // SetList::EARLY_RETURN,
        // SetList::INSTANCEOF,
        // SetList::NAMING,
        SetList::PHP_82,
        // SetList::PRIVATIZATION,
        // SetList::STRICT_BOOLEANS,
        // SetList::TYPE_DECLARATION,
        LevelSetList::UP_TO_PHP_82,
    ]);

    // symfony rules
    /* $rectorConfig->symfonyContainerPhp(
        __DIR__ . '/var/cache/website/dev/App_KernelDevDebugContainer.xml'
    );

    $rectorConfig->symfonyContainerXml(
        __DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml'
    ); */

    /* $rectorConfig->import(SymfonySetList::SYMFONY_63);

    $parameters = $rectorConfig->parameters();
    
    $parameters->set(Option::SOURCE, [__DIR__ . '/src']);

    $parameters->set(
        Option::class,
        __DIR__ . '/var/cache/dev/AppKernelDevDebugContainer.xml'
    ); */

    $rectorConfig->sets([
        SymfonySetList::SYMFONY_63,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);

        // doctrine rules
        $rectorConfig->sets([
            DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
            DoctrineSetList::DOCTRINE_CODE_QUALITY,
        ]);
    
        // phpunit rules
        $rectorConfig->sets([
            PHPUnitSetList::PHPUNIT_100,
            PHPUnitSetList::PHPUNIT_CODE_QUALITY,
            PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
        ]);

};

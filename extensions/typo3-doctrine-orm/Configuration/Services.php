<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm;

use RinyVT\Typo3DoctrineOrm\DependencyInjection\DoctrinePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->addCompilerPass(new DoctrinePass());
};

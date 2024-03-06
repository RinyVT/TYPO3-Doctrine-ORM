<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrinePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->setAlias('doctrine.orm.entity_manager', 'doctrine.orm.default_entity_manager');
        $container->getAlias('doctrine.orm.entity_manager')->setPublic(true);
    }
}

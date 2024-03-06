<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use RinyVT\Typo3DoctrineOrm\Service\TcaBuilder;
use TYPO3\CMS\Core\Configuration\Event\AfterTcaCompilationEvent;

class TcaBuiltListener
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TcaBuilder $tcaBuilder
    ) {
    }

    public function __invoke(AfterTcaCompilationEvent $event): void
    {
        $GLOBALS['TCA'] = $event->getTca();
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($metadata as $classMetadata) {
            $GLOBALS['TCA'][$classMetadata->getTableName()] = $this->tcaBuilder->build($classMetadata);
        }

        $event->setTca($GLOBALS['TCA']);
    }
}

<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\Property\TypeConverter;

use Doctrine\ORM\EntityManager;
use RinyVT\Typo3DoctrineOrm\Entity\AbstractEntity;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

class EntityConverter extends AbstractTypeConverter
{
    public function __construct(
        protected EntityManager $entityManager
    ) {
    }

    public function convertFrom(
        $source,
        string $targetType,
        array $convertedChildProperties = [],
        PropertyMappingConfigurationInterface $configuration = null
    ) {
        if (class_exists($targetType) && is_a($targetType, AbstractEntity::class, true)) {
            return $this->entityManager->getRepository($targetType)->find((int)$source);
        }
        return null;
    }
}
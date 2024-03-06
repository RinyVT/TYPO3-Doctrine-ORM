<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\MetaDataDriver;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Persistence\Mapping\ClassMetadata;
use RinyVT\Typo3DoctrineOrm\Mapping\ExtendTable;
use RinyVT\Typo3DoctrineOrm\Utility\NamingUtility;

class AttributeDriver extends \Doctrine\ORM\Mapping\Driver\AttributeDriver
{
    public function loadMetadataForClass($className, ClassMetadata $metadata): void
    {
        parent::loadMetadataForClass($className, $metadata);
        assert($metadata instanceof ClassMetadataInfo);

        // Already created in parent
        $reflectionClass = $metadata->getReflectionClass();
        $classAttributes = $this->reader->getClassAttributes($reflectionClass);

        // Set table name if not set
        if (!isset($classAttributes[Table::class])) {
            if (isset($classAttributes[ExtendTable::class])) {
                $tableName = $classAttributes[ExtendTable::class]->name;
            } else {
                $tableName = NamingUtility::getTableNameFromEntity($className);
            }
            $metadata->setPrimaryTable(['name' => $tableName]);
        }

        // Set repository if not set
        if (empty($metadata->customRepositoryClassName)) {
            $repositoryClassName = NamingUtility::getRepositoryNameFromEntity($className);
            if (class_exists($repositoryClassName)) {
                $metadata->setCustomRepositoryClass($repositoryClassName);
            }
        }
    }
}

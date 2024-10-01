<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use RinyVT\Typo3DoctrineOrm\Utility\NamingUtility;
use TYPO3\CMS\Core\Database\Event\AlterTableDefinitionStatementsEvent;

class TableDefinitionsListener
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(AlterTableDefinitionStatementsEvent $event): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $allMetaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaSql = $schemaTool->getCreateSchemaSql($allMetaData);

        if (count($schemaSql) < 1) {
            return;
        }

        // temporary filter alter table (foreign keys)
        array_map(static function ($sql) use ($event) {
            if (!str_contains($sql, 'FOREIGN KEY')) {
                $event->addSqlData($sql . ';');
            }
        }, $schemaSql);

        // create (counter) fields for associations in parent table (TYPO3 specific)
        foreach ($allMetaData as $classMetaData) {
            foreach ($classMetaData->getAssociationMappings() as $associationMapping) {
                if (!in_array($associationMapping['type'], [ClassMetadata::ONE_TO_MANY, ClassMetadata::MANY_TO_MANY], true)) {
                    continue;
                }
                $event->addSqlData(
                    'CREATE TABLE ' . $classMetaData->getTableName() . ' (' . NamingUtility::camelCaseToUnderscore($associationMapping['fieldName']) . ' INT DEFAULT NULL);'
                );
            }
        }
    }
}

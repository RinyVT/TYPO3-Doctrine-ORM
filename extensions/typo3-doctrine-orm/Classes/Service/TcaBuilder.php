<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\Driver\AttributeReader;
use RinyVT\Typo3DoctrineOrm\Mapping\ExtendTable;
use RinyVT\Typo3DoctrineOrm\Utility\NamingUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TcaBuilder
{
    protected const IGNORE_FIELDS = ['uid', 'pid'];

    protected string $llPrefix;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected AttributeReader $reader
    ) {
    }

    public function build(ClassMetadata $classMetadata): array
    {
        $reflectionClass = $classMetadata->getReflectionClass();
        $classAttributes = $reflectionClass ? $this->reader->getClassAttributes($reflectionClass) : [];

        if (isset($classAttributes[ExtendTable::class])) {
            $extendTableName = $classAttributes[ExtendTable::class]->name;
            $this->setLLPrefix($classMetadata, $extendTableName);
            return $this->extendTca($classMetadata, $reflectionClass, $extendTableName);
        }

        $this->setLLPrefix($classMetadata, $classMetadata->getTableName());
        return $this->createTca($classMetadata);
    }

    protected function setLLPrefix(ClassMetadata $classMetadata, string $tableName): void
    {
        $classParts = explode('\\', $classMetadata->getName());
        $extensionKey = GeneralUtility::camelCaseToLowerCaseUnderscored($classParts[1]);

        $this->llPrefix = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/Tca.xlf:' . $tableName;
    }

    protected function extendTca(
        ClassMetadata $classMetadata,
        \ReflectionClass $reflectionClass,
        string $tableName
    ): array {
        foreach ($reflectionClass->getProperties() as $property) {
            // TODO
        }

        return $GLOBALS['TCA'][$tableName];
    }

    protected function createTca(ClassMetadata $classMetadata): array
    {
        $columns = $this->createColumns(
            array_merge($classMetadata->fieldMappings, $classMetadata->getAssociationMappings())
        );

        return [
            'ctrl' => [
                'title' => $this->llPrefix,
                'label' => array_key_first($columns)
            ],
            'columns' => $columns,
            'types' => [
                0 => ['showitem' => implode(',', array_keys($columns))]
            ]
        ];
    }

    protected function createColumns(array $fields): array
    {
        $tcaColumns = [];
        foreach ($fields as $field => $fieldInfo) {
            if (in_array($field, self::IGNORE_FIELDS, true)) {
                continue;
            }
            $columnName = NamingUtility::camelCaseToUnderscore($this->determineColumnName($fieldInfo));
            $tcaColumns[$columnName] = [
                'label' => $this->llPrefix . '.' . NamingUtility::removeIdSuffixFromField($columnName),
                'config' => $this->getConfig($fieldInfo)
            ];
        }
        return $tcaColumns;
    }

    protected function determineColumnName(array $fieldInfo): string
    {
        if (isset($fieldInfo['columnName'])) {
            return $fieldInfo['columnName'];
        }

        if (isset($fieldInfo['sourceToTargetKeyColumns'])) {
            return array_key_first($fieldInfo['sourceToTargetKeyColumns']);
        }

        return $fieldInfo['fieldName'];
    }

    protected function getConfig(array $fieldInfo): array
    {
        return match ($fieldInfo['type']) {
            ClassMetadataInfo::ONE_TO_ONE, ClassMetadataInfo::MANY_TO_ONE => $this->selectSingle($fieldInfo),
            ClassMetadataInfo::ONE_TO_MANY => $this->inlineRecords($fieldInfo),
            ClassMetadataInfo::MANY_TO_MANY => $this->mmTable($fieldInfo),
            'text' => [
                'type' => 'text'
            ],
            default => [
                'type' => 'input'
            ],
        };
    }

    protected function selectSingle(array $fieldInfo): array
    {
        return [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'foreign_table' => $this->entityManager->getClassMetadata($fieldInfo['targetEntity'])->getTableName(),
        ];
    }

    protected function inlineRecords(array $fieldInfo): array
    {
        return [
            'type' => 'inline',
            'foreign_table' => $this->entityManager->getClassMetadata($fieldInfo['targetEntity'])->getTableName(),
            'foreign_field' => NamingUtility::addIdSuffixToField($fieldInfo['mappedBy'])
        ];
    }

    protected function mmTable(array $fieldInfo): array
    {
        return [
            'type' => 'group',
            'allowed' => $this->entityManager->getClassMetadata($fieldInfo['targetEntity'])->getTableName(),
            'MM' => $fieldInfo['joinTable']['name']
        ];
    }
}

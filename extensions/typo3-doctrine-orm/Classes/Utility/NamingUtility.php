<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class NamingUtility
{
    protected const FIELD_ID_SUFFIX = '_id';

    public static function addIdSuffixToField(string $fieldName): string
    {
        return $fieldName . self::FIELD_ID_SUFFIX;
    }

    public static function removeIdSuffixFromField(string $fieldName): string
    {
        return str_replace(self::FIELD_ID_SUFFIX, '', $fieldName);
    }

    public static function camelCaseToUnderscore(string $fieldName): string
    {
        return GeneralUtility::camelCaseToLowerCaseUnderscored($fieldName);
    }

    public static function getTableNameFromEntity(string $entityClassName): string
    {
        // Quick and dirty way to set table name
        $classParts = explode('\\', $entityClassName);
        return 'tx_' . strtolower($classParts[1]) . '_entity_' . strtolower(end($classParts));
    }

    public static function getRepositoryNameFromEntity(string $className): string
    {
        return str_replace('Entity', 'Repository', $className . 'Repository');
    }
}

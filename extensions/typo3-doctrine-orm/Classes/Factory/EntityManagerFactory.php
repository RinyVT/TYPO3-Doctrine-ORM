<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\Factory;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use RinyVT\Typo3DoctrineOrm\MetaDataDriver\AttributeDriver;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Doctrine\ORM\EntityManager;

class EntityManagerFactory
{
    public static function create(): EntityManager
    {
        // Cheating by instantiating the connectionPool through "new":  https://review.typo3.org/c/Packages/TYPO3.CMS/+/72558
        $connectionPool = new ConnectionPool();
        $connection = $connectionPool->getConnectionByName('Default');

        $configuration = new Configuration();
        $configuration->setMetadataDriverImpl(new AttributeDriver(self::getEntityPaths()));
        $configuration->setProxyNamespace('Proxies');
        $configuration->setProxyDir(Environment::getVarPath() . '/orm');
        $configuration->setNamingStrategy(new UnderscoreNamingStrategy(CASE_LOWER));

        return new EntityManager($connection, $configuration);
    }

    protected static function getEntityPaths(): array
    {
        $packageManager = GeneralUtility::makeInstance(PackageManager::class);
        $entityPaths = [];
        foreach ($packageManager->getActivePackages() ?? [] as $package) {
            $entityPath = $package->getPackagePath() . 'Classes/Entity';
            if (is_dir($entityPath)) {
                $entityPaths[] = $entityPath;
            }
        }
        return $entityPaths;
    }
}

<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addTypoScriptSetup(
    "@import 'EXT:sitepackage/Configuration/TypoScript/setup.typoscript'"
);

<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

(static function() {
    ExtensionUtility::registerPlugin(
        'DoctrineBlog',
        'Post',
        'Post list',
        'EXT:doctrine_blog/Resources/Public/Icons/Extension.svg'
    );
})();

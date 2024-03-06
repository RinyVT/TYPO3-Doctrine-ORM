<?php

use RinyVT\DoctrineBlog\Controller\PostController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

(static function () {
    ExtensionUtility::configurePlugin(
        'DoctrineBlog',
        'Post',
        [PostController::class => 'list,show,addComment'],
        [PostController::class => 'list,show,addComment'],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
})();

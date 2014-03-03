<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id' => 'srunit',
    'title' => 'sR Unit Extension',
    'description' => array(
        'de' => 'sR Unit Extension',
        'en' => 'sR Unit Extension',
    ),
    'thumbnail' => 'superreal.png',
    'version' => '1.0',
    'author' => 'superReal GmbH',
    'url' => 'http://www.supereal.de',
    'email' => 'it@superreal.de',
    'extend' => array(
        'oxutilsobject' => 'srunit/core/sroxutilsobject',
        'oxutilsserver' => 'srunit/core/srunitoxutilsserver',
    ),
    'files' => array(),
    'blocks' => array(),
    'templates' => array(),
    'settings' => array(),
);

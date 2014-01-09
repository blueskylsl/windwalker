<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Windwalker\DI\Container;

$version = new JVersion;

if (!$version->isCompatible('3.2'))
{
	throw new Exception('Windwalker need Joomla! 3.2 or higher.');
}

// Import Windwalker autoload.
include_once __DIR__ . '/autoload.php';

include_once __DIR__ . '/PHP/methods.php';

define('WINDWALKER', dirname(__DIR__));

define('WINDWALKER_SOURCE', __DIR__);

// Register global provider
$container = Container::getInstance();

$container->registerServiceProvider(new \Windwalker\Provider\SystemProvider);

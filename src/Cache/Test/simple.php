<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU Lesser General Public License version 2.1 or later.
 */

include_once __DIR__ . '/../../../vendor/autoload.php';

use Windwalker\Cache\Cache;
use Windwalker\Cache\Storage\FileStorage;

$cache = new Cache(new FileStorage(__DIR__ . '/cache'));

$cache->set('flower', array('flower' => 'sakura'));

$data = $cache->get('flower');

print_r($data);

<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Router;

/**
 * Interface RouterInterface
 */
interface RouterInterface
{
	/**
	 * match
	 *
	 * @param string $route
	 *
	 * @return  mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	public function match($route);
}


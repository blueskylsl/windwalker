<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2008 - 2014 Asikart.com. All rights reserved.
 * @license    GNU Lesser General Public License version 2.1 or later.
 */

namespace Windwalker\Cache\DataHandler;

/**
 * Interface DataHandlerInterface
 */
interface DataHandlerInterface
{
	/**
	 * unserialize
	 *
	 * @param string $data
	 *
	 * @return  mixed
	 */
	public function encode($data);

	/**
	 * serialize
	 *
	 * @param mixed $data
	 *
	 * @return  string
	 */
	public function decode($data);
}


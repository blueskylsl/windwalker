<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2008 - 2014 Asikart.com. All rights reserved.
 * @license    GNU Lesser General Public License version 2.1 or later.
 */

namespace Windwalker\DataMapper\Entity;

use Windwalker\Data\Data;

/**
 * Entity is a Data object sub class, we can set fields of this object
 * then help us filter non necessary values to prevent error when inserting to database.
 */
class Entity extends Data
{
	/**
	 * Constructor.
	 *
	 * @param array $fields
	 * @param mixed $data
	 */
	public function __construct($fields = null, $data = null)
	{
		if ($fields)
		{
			$this->addFields($fields);
		}

		$this->prepare();

		parent::__construct($data);

	}

	/**
	 * Prepare your logic.
	 *
	 * @return  void
	 */
	protected function prepare()
	{
		// Override this method if you need.
	}

	/**
	 * Add a field to this entity.
	 *
	 * @param array $fields Fields array.
	 *
	 * @return  Entity Return self to support chaining.
	 */
	public function addFields($fields)
	{
		foreach ($fields as $field)
		{
			$this->addField($field);
		}

		return $this;
	}

	/**
	 * Add a field.
	 *
	 * @param string $field Field name.
	 *
	 * @return  Entity Return self to support chaining.
	 */
	public function addField($field)
	{
		$this->$field = null;

		return $this;
	}

	/**
	 * Remove field from this entity.
	 *
	 * @param string $field Field name.
	 *
	 * @return  Entity Return self to support chaining.
	 */
	public function removeField($field)
	{
		unset($this->$field);

		return $this;
	}

	/**
	 * Set a value to entity. If property not exists, will not set it in.
	 *
	 * @param string $field Field name.
	 * @param mixed  $value Value.
	 *
	 * @return  Entity Return self to support chaining.
	 */
	public function set($field, $value = null)
	{
		if (!property_exists($this, $field))
		{
			return $this;
		}

		return parent::set($field, $value);
	}

	/**
	 * Magic method to set property.
	 *
	 * @param string $field Field name.
	 * @param mixed  $value Value.
	 *
	 * @return  void
	 */
	public function __set($field, $value = null)
	{
		$this->$field = $value;
	}
}

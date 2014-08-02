<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Windwalker\Data;

/**
 * Data object to store values.
 *
 * @since 2.0
 */
class Data implements DataInterface, \IteratorAggregate, \ArrayAccess, \Countable
{
	/**
	 * Constrictor.
	 *
	 * @param mixed $data
	 */
	public function __construct($data = null)
	{
		if (null !== $data)
		{
			$this->bind($data);
		}
	}

	/**
	 * Bind the data into this object.
	 *
	 * @param   mixed    $values       The data array or object.
	 * @param   boolean  $replaceNulls Replace null or not.
	 *
	 * @return  Data Return self to support chaining.
	 *
	 * @throws \InvalidArgumentException
	 */
	public function bind($values, $replaceNulls = false)
	{
		// Check properties type.
		if (!is_array($values) && !is_object($values))
		{
			throw new \InvalidArgumentException(sprintf('Please bind array or object, %s given.', gettype($values)));
		}

		// If is Traversable, get iterator.
		if ($values instanceof \Traversable)
		{
			$values = iterator_to_array($values);
		}
		// If is object, convert it to array
		elseif (is_object($values))
		{
			$values = (array) $values;
		}

		// Bind the properties.
		foreach ($values as $field => $value)
		{
			// Check if the value is null and should be bound.
			if ($value === null && !$replaceNulls)
			{
				continue;
			}

			// Set the property.
			$this->$field = $value;
		}

		return $this;
	}

	/**
	 * Set value.
	 *
	 * @param string $field The field to set.
	 * @param mixed  $value The value to set.
	 *
	 * @return  Data Return self to support chaining.
	 */
	public function set($field, $value = null)
	{
		if (strpos($field, "\0") === 0)
		{
			throw new \InvalidArgumentException("Cannot access property start with '\\0', got: " . $field);
		}
		
		$this->$field = $value;

		return $this;
	}

	/**
	 * Get value.
	 *
	 * @param string $field   The field to get.
	 * @param mixed  $default The default value if not exists.
	 *
	 * @return  mixed The value we want ot get.
	 */
	public function get($field, $default = null)
	{
		if (strpos($field, "\0") === 0)
		{
			throw new \InvalidArgumentException("Cannot access property start with '\\0', got: " . $field);
		}
		
		if (isset($this->$field))
		{
			return $this->$field;
		}

		return $default;
	}

	/**
	 * Set value.
	 *
	 * @param string $field The field to set.
	 * @param mixed  $value The value to set.
	 *
	 * @return  void
	 */
	public function __set($field, $value = null)
	{
		$this->set($field, $value);
	}

	/**
	 * Get value.
	 *
	 * @param string $field The field to get.
	 *
	 * @return  mixed The value we want ot get.
	 */
	public function __get($field)
	{
		return $this->get($field);
	}

	/**
	 * Retrieve an external iterator
	 *
	 * @return \Traversable An instance of an object implementing Iterator or Traversable
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this);
	}

	/**
	 * Is a property exists or not.
	 *
	 * @param mixed $offset Offset key.
	 *
	 * @return  boolean
	 */
	public function offsetExists($offset)
	{
		return isset($this->$offset);
	}

	/**
	 * Get a property.
	 *
	 * @param mixed $offset Offset key.
	 *
	 * @return  mixed The value to return.
	 */
	public function offsetGet($offset)
	{
		if (strpos($offset, "\0") === 0)
		{
			throw new \InvalidArgumentException("Cannot access property start with '\\0', got: " . $field);
		}
		
		return $this->$offset;
	}

	/**
	 * Set a value to property.
	 *
	 * @param mixed $offset Offset key.
	 * @param mixed $value  The value to set.
	 *
	 * @return  void
	 */
	public function offsetSet($offset, $value)
	{
		if (strpos($offset, "\0") === 0)
		{
			throw new \InvalidArgumentException("Cannot access property start with '\\0', got: " . $field);
		}
		
		$this->$offset = $value;
	}

	/**
	 * Unset a property.
	 *
	 * @param mixed $offset Offset key to unset.
	 *
	 * @return  void
	 */
	public function offsetUnset($offset)
	{
		if (strpos($offset, "\0") === 0)
		{
			throw new \InvalidArgumentException("Cannot access property start with '\\0', got: " . $field);
		}
		
		unset($this->$offset);
	}

	/**
	 * Count this object.
	 *
	 * @return  int
	 */
	public function count()
	{
		return count(get_object_vars($this));
	}

	/**
	 * Is this object empty?
	 *
	 * @return  boolean
	 */
	public function isNull()
	{
		return (boolean) !count($this);
	}
}

<?php
/**
 * Part of the Joomla Framework Filesystem Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\Filesystem;

use Windwalker\Filesystem\Exception\FilesystemException;

/**
 * A File handling class
 *
 * @since  1.0
 */
class File
{
	/**
	 * Strips the last extension off of a file name
	 *
	 * @param   string  $file  The file name
	 *
	 * @return  string  The file name without the extension
	 *
	 * @since   1.0
	 */
	public static function stripExt($file)
	{
		return preg_replace('#\.[^.]*$#', '', $file);
	}

	/**
	 * Makes the file name safe to use
	 *
	 * @param   string  $file        The name of the file [not full path]
	 * @param   array   $stripChars  Array of regex (by default will remove any leading periods)
	 *
	 * @return  string  The sanitised string
	 *
	 * @since   1.0
	 */
	public static function makeSafe($file, array $stripChars = array('#^\.#'))
	{
		$regex = array_merge(array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#'), $stripChars);

		$file = preg_replace($regex, '', $file);

		// Remove any trailing dots, as those aren't ever valid file names.
		$file = rtrim($file, '.');

		return $file;
	}

	/**
	 * Copies a file
	 *
	 * @param   string $src   The path to the source file
	 * @param   string $dest  The path to the destination file
	 * @param   bool   $force Force copy.
	 *
	 * @throws \UnexpectedValueException
	 * @throws Exception\FilesystemException
	 * @return  boolean  True on success
	 *
	 * @since   1.0
	 */
	public static function copy($src, $dest, $force = false)
	{
		// Check src path
		if (!is_readable($src))
		{
			throw new \UnexpectedValueException(__METHOD__ . ': Cannot find or read file: ' . $src);
		}

		// Check folder exists
		$dir = dirname($dest);

		if (!is_dir($dir))
		{
			Folder::create($dir);
		}

		// Check is a folder or file
		if (file_exists($dest))
		{
			if ($force)
			{
				Filesystem::delete($dest);
			}
			else
			{
				throw new FilesystemException($dest . ' has exists, copy faieed.');
			}
		}

		if (!@ copy($src, $dest))
		{
			throw new FilesystemException(__METHOD__ . ': Copy failed.');
		}

		return true;
	}

	/**
	 * Delete a file or array of files
	 *
	 * @param   mixed  $file  The file name or an array of file names
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.0
	 * @throws  FilesystemException
	 */
	public static function delete($file)
	{
		$files = (array) $file;

		foreach ($files as $file)
		{
			$file = Path::clean($file);

			// Try making the file writable first. If it's read-only, it can't be deleted
			// on Windows, even if the parent folder is writable
			@chmod($file, 0777);

			// In case of restricted permissions we zap it one way or the other
			// as long as the owner is either the webserver or the ftp
			if (!@ unlink($file))
			{
				throw new FilesystemException(__METHOD__ . ': Failed deleting ' . basename($file));
			}
		}

		return true;
	}

	/**
	 * Moves a file
	 *
	 * @param   string $src   The path to the source file
	 * @param   string $dest  The path to the destination file
	 * @param   bool   $force Force move it.
	 *
	 * @throws Exception\FilesystemException
	 * @return  boolean  True on success
	 *
	 * @since   1.0
	 */
	public static function move($src, $dest, $force = false)
	{
		// Check src path
		if (!is_readable($src))
		{
			return 'Cannot find source file.';
		}

		// Delete first if exists
		if (file_exists($dest))
		{
			if ($force)
			{
				Filesystem::delete($dest);
			}
			else
			{
				throw new FilesystemException('File: ' . $dest . ' exists, move failed.');
			}
		}

		if (!@ rename($src, $dest))
		{
			throw new FilesystemException(__METHOD__ . ': Rename failed.');
		}

		return true;
	}

	/**
	 * Write contents to a file
	 *
	 * @param   string   $file         The full file path
	 * @param   string   $buffer      The buffer to write
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.0
	 * @throws  FilesystemException
	 */
	public static function write($file, $buffer)
	{
		@set_time_limit(ini_get('max_execution_time'));

		// If the destination directory doesn't exist we need to create it
		if (!file_exists(dirname($file)))
		{
			Folder::create(dirname($file));
		}

		$file = Path::clean($file);
		$ret = is_int(file_put_contents($file, $buffer)) ? true : false;

		return $ret;
	}

	/**
	 * Moves an uploaded file to a destination folder
	 *
	 * @param   string   $src          The name of the php (temporary) uploaded file
	 * @param   string   $dest         The path (including filename) to move the uploaded file to
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.0
	 * @throws  FilesystemException
	 */
	public static function upload($src, $dest)
	{
		// Ensure that the path is valid and clean
		$dest = Path::clean($dest);

		// Create the destination directory if it does not exist
		$baseDir = dirname($dest);

		if (!file_exists($baseDir))
		{
			Folder::create($baseDir);
		}

		if (is_writeable($baseDir) && move_uploaded_file($src, $dest))
		{
			// Short circuit to prevent file permission errors
			if (Path::setPermissions($dest))
			{
				return true;
			}
			else
			{
				throw new FilesystemException(__METHOD__ . ': Failed to change file permissions.');
			}
		}
		else
		{
			throw new FilesystemException(__METHOD__ . ': Failed to move file.');
		}

		return false;
	}
}
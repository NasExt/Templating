<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Templating;

use Nette\Object;

/**
 * FilesList
 *
 * @author Dusan Hudak
 */
class FilesList extends Object
{
	/** @var  array */
	private $files;

	/** @var  IFilesFormatterLogger */
	private $logger;

	/** @var  string */
	private $name;


	/**
	 * @param array $files
	 * @param string $name
	 * @param IFilesFormatterLogger|NULL $logger
	 * @return FilesList
	 */
	public function __construct($name, array $files, $logger = NULL)
	{
		$this->name = $name;
		$this->files = $files;
		$this->logger = $logger;

		if ($this->logger) {
			$this->logger->logFiles($name, $files);
		}

		return $this;
	}


	/**
	 * @param string $file
	 * @param bool $onTop
	 * @return FilesList $this
	 */
	public function addFile($file, $onTop = FALSE)
	{
		if ($this->logger) {
			$this->logger->logFiles($this->name, array($file), $onTop);
		}
		$this->files[] = $file;
		return $this;
	}


	/**
	 * @param array $files
	 * @param bool $onTop
	 * @return FilesList $this
	 */
	public function addFiles($files, $onTop = FALSE)
	{
		foreach ($files as $file) {
			$this->addFile($file, $onTop);
		}
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return array
	 */
	public function getFiles()
	{
		return $this->files;
	}


	/**
	 * Return first existing file from files
	 * @return string|bool
	 */
	public function getTemplateFile()
	{
		foreach ($this->files as $file) {
			if (is_file($file)) {
				return $file;
				break;
			}
		}
		return FALSE;
	}


	/**
	 * Return all existing files from files
	 * @return array
	 */
	public function getTemplateFiles()
	{
		$templateFiles = array();

		foreach ($this->files as $file) {
			if (is_file($file)) {
				$templateFiles[] = $file;
			}
		}
		return $templateFiles;
	}
}

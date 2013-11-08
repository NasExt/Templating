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
 * Formatter
 *
 * @author Dusan Hudak
 */
class Formatter extends Object
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
	 * @param IFilesFormatterLogger $logger
	 * @return Formatter
	 */
	public function __construct($name, array $files, IFilesFormatterLogger $logger)
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
	 * @return array
	 */
	public function getFiles()
	{
		return $this->files;
	}


	/**
	 * @param string $file
	 * @return Formatter $this
	 */
	public function addFile($file)
	{
		if ($this->logger) {
			$this->logger->logFiles($this->name, array($file));
		}
		$this->files[] = $file;
		return $this;
	}


	/**
	 * @param array $files
	 * @return Formatter $this
	 */
	public function addFiles($files)
	{
		foreach ($files as $file) {
			$this->addFile($file);
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
}

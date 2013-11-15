<?php
/**
 * This file is part of the NasExt extensions of Nette Framework.
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 * Nella Framework (http://nellafw.org)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Templating;

use Nette\Object;
use Nette\Utils\Strings;

/**
 * TemplateFilesFormatter
 *
 * @author Patrik Votoček
 * @author Dusan Hudak
 */
class TemplateFilesFormatter extends Object implements ITemplateFilesFormatter
{

	const MODULE_SUFFIX = 'Module';

	/** @var bool */
	public $useModuleSuffix = TRUE;

	/** @var \SplPriorityQueue */
	private $dirs;

	/** @var IFilesFormatterLogger|NULL */
	private $logger;


	public function __construct()
	{
		$this->dirs = new \SplPriorityQueue;
		$this->logger = NULL;
	}


	/**
	 * @param string $dir
	 * @param int $priority
	 * @return TemplateFilesFormatter
	 */
	public function addDir($dir, $priority = 5)
	{
		$this->dirs->insert($dir, $priority);
		return $this;
	}


	/**
	 * @param IFilesFormatterLogger $logger
	 * @return TemplateFilesFormatter
	 */
	public function setLogger(IFilesFormatterLogger $logger)
	{
		$this->logger = $logger;
		return $this;
	}


	/**
	 * Formats layout template file names
	 * @param string $presenterName
	 * @param string $layout
	 * @return FilesList
	 */
	public function formatLayoutTemplateFiles($presenterName, $layout = 'layout')
	{
		$path = str_replace(':', '/', substr($presenterName, 0, strrpos($presenterName, ':')));
		$subPath = substr($presenterName, strrpos($presenterName, ':') !== FALSE ? strrpos($presenterName, ':') + 1 : 0);
		if ($path) {
			$path .= '/';
		}

		if ($this->useModuleSuffix && $path) {
			$path = str_replace('/', self::MODULE_SUFFIX . '/', $path);
		}

		$generator = function ($dir) use ($presenterName, $path, $subPath, $layout) {
			$files = array();
			// classic modules templates
			if (strpos($presenterName, ':') !== FALSE) {
				$files[] = $dir . '/' . $path . "templates/$subPath/@$layout.latte";
				$files[] = $dir . '/' . $path . "templates/$subPath.@$layout.latte";
				$files[] = $dir . '/' . $path . "templates/@$layout.latte";
			}
			// classic templates
			$files[] = $dir . '/templates/' . $path . "$subPath/@$layout.latte";
			$files[] = $dir . '/templates/' . $path . "$subPath.@$layout.latte";
			$files[] = $dir . '/templates/' . $path . "@$layout.latte";

			$file = $dir . "/templates/@$layout.latte";
			if (!in_array($file, $files)) {
				$files[] = $file;
			}

			return $files;
		};

		$files = array();
		$dirs = clone $this->dirs;
		foreach ($dirs as $dir) {
			$files = array_merge($files, $generator($dir));
		}

		return new FilesList("$presenterName:$layout", $files, $this->logger);
	}


	/**
	 * Formats view template file names
	 * @param string $presenterName
	 * @param string $presenterView
	 * @return FilesList
	 */
	public function formatTemplateFiles($presenterName, $presenterView)
	{
		$path = str_replace(':', '/', substr($presenterName, 0, strrpos($presenterName, ':')));
		$subPath = substr($presenterName, strrpos($presenterName, ':') !== FALSE ? strrpos($presenterName, ':') + 1 : 0);
		if ($path) {
			$path .= '/';
		}

		if ($this->useModuleSuffix && $path) {
			$path = str_replace('/', self::MODULE_SUFFIX . '/', $path);
		}

		$generator = function ($dir) use ($presenterName, $path, $subPath, $presenterView) {
			$files = array();
			// classic modules templates
			if (strpos($presenterName, ':') !== FALSE) {
				$files[] = $dir . '/' . $path . "templates/$subPath/$presenterView.latte";
				$files[] = $dir . '/' . $path . "templates/$subPath.$presenterView.latte";
				$files[] = $dir . '/' . $path . "templates/$subPath/@global.latte";
				$files[] = $dir . '/' . $path . 'templates/@global.latte';
			}
			// classic templates
			$files[] = $dir . '/templates/' . $path . "$subPath/$presenterView.latte";
			$files[] = $dir . '/templates/' . $path . "$subPath.$presenterView.latte";
			$files[] = $dir . '/templates/' . $path . "$subPath/@global.latte";
			$files[] = $dir . '/templates/' . $path . '@global.latte';

			$file = $dir . '/templates/@global.latte';
			if (!in_array($file, $files)) {
				$files[] = $file;
			}

			return $files;
		};

		$files = array();
		$dirs = clone $this->dirs;
		foreach ($dirs as $dir) {
			$files = array_merge($files, $generator($dir));
		}

		return new FilesList("$presenterName:$presenterView", $files, $this->logger);
	}


	/**
	 * Formats component template file names
	 * @param string $presenterName
	 * @param string $presenterView
	 * @param string $controlClass
	 * @return FilesList
	 */
	public function formatComponentTemplateFiles($presenterName, $presenterView, $controlClass)
	{
		if (Strings::endsWith($controlClass, 'Control')) {
			$controlClass = substr($controlClass, 0, -7);
		}
		$name = substr($controlClass, strpos($controlClass, '\\'));
		$path = str_replace('\\', '/', substr($name, 0, strrpos($name, '\\')));
		$subPath = substr($name, strrpos($name, '\\') !== FALSE ? strrpos($name, '\\') + 1 : 0);
		if ($path) {
			$path .= '/';
		}

		$generator = function ($dir) use ($name, $path, $subPath, $presenterView) {
			$files = array();

			if ($presenterView) {
				$files[] = $dir . '/' . $path . "templates/$subPath/$presenterView.latte";
				$files[] = $dir . '/' . $path . "templates/$subPath.$presenterView.latte";
			} else {
				$files[] = $dir . '/' . $path . "templates/$subPath.latte";
			}
			$files[] = $dir . '/' . $path . "templates/$subPath/@global.latte";

			if ($presenterView) {
				$files[] = $dir . '/' . $path . "$subPath/$presenterView.latte";
				$files[] = $dir . '/' . $path . "$subPath.$presenterView.latte";
			} else {
				$files[] = $dir . '/' . $path . "$subPath.latte";
			}
			$files[] = $dir . '/' . $path . "$subPath/@global.latte";
			$files[] = $dir . '/' . $path . '@global.latte';

			return $files;
		};

		$files = array();
		$dirs = clone $this->dirs;
		foreach ($dirs as $dir) {
			$files = array_merge($files, $generator($dir));
		}

		return new FilesList("$presenterName:$presenterView:$controlClass", $files, $this->logger);
	}


	/**
	 * Format FileTemplate Files
	 * @param string $template
	 * @return FilesList
	 */
	public function formatFileTemplateFiles($template)
	{
		$files = array();
		$dirs = clone $this->dirs;
		foreach ($dirs as $dir) {
			$files[] = $dir . "/templates/$template";
		}

		return new FilesList(substr(strrchr($template, '/'), 1), $files, $this->logger);
	}
}

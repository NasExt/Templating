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

namespace NasExt\Templating\Diagnostics;

use NasExt\Templating\IFilesFormatterLogger;
use NasExt\Templating\ITemplateFilesFormatter;
use Nette\Diagnostics\Bar;
use Nette\Diagnostics\Debugger;
use Nette\Diagnostics\IBarPanel;
use Nette\SmartObject;

/**
 * FilesPanel
 *
 * @author Patrik Votočke
 * @author Dusan Hudak
 */
class FilesPanel implements IBarPanel, IFilesFormatterLogger
{
	use SmartObject;

	/** @var array */
	private $files = array();


	/**
	 * @param string $name
	 * @param array $files
	 * @param bool $onTop
	 */
	public function logFiles($name, array $files, $onTop = FALSE)
	{
		if (array_key_exists($name, $this->files)) {
			if ($onTop) {
				$this->files[$name] = array_merge($files, $this->files[$name]);
			} else {
				$this->files[$name] = array_merge($this->files[$name], $files);
			}
		} else {
			$this->files[$name] = $files;
		}
	}


	/**
	 * Renders HTML code for custom tab
	 *
	 * @return string
	 */
	public function getTab()
	{
		return '<span title="TemplateFilesFormatter">' . '<img src="
		data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSB
		JbWFnZVJlYWR5ccllPAAAANBJREFUeNpi/P//P8OiRYumMTAwZDKQBqbHxcVlMYAMmD9//v9///
		6RhEF6QHpZQEb9/fsXbGRU6yGirF5WbQfXw4RsACkApgfFBfOKzcg3AOSfpN5TYME5hSYMe/fux
		anZx8cH1Qt//vzBajo+ANOD1QBgKBNtAAuMA/ICsgE2NjY4NYPUYhiA7oKjR4/iDQO8XiArDNC9
		gA/g9EJvkipYEmSAiYkJVs2MjIyYgQhzcvG820QlosUVYthT4uIKa/JSItDJ00tKSkjOziACIMA
		AlcrRi+NYy3QAAAAASUVORK5CYII=" />' . '</span>';
	}


	/**
	 * Renders HTML code for custom panel
	 *
	 * @return string
	 */
	public function getPanel()
	{
		$files = $this->files;
		ob_start();
		require __DIR__ . '/templates/FilesPanel.panel.phtml';
		return ob_get_clean();
	}


	/**
	 * @param ITemplateFilesFormatter $templateFilesFormatter
	 * @return FilesPanel
	 */
	public static function register(ITemplateFilesFormatter $templateFilesFormatter)
	{
		/** @var FilesPanel $panel */
		$panel = new static();
		static::getDebuggerBar()->addPanel($panel);
		$templateFilesFormatter->setLogger($panel);
		return $panel;
	}


	/**
	 * @return Bar
	 */
	private static function getDebuggerBar()
	{
		return method_exists('Nette\Diagnostics\Debugger', 'getBar') ? Debugger::getBar() : Debugger::$bar;
	}
}

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

/**
 * ITemplateFilesFormatter
 *
 * @author Patrik Votoček
 * @author Dusan Hudak
 */
interface ITemplateFilesFormatter
{
	/**
	 * Formats layout template file names
	 * @param string $presenterName
	 * @param string $layout
	 * @return array
	 */
	public function formatLayoutTemplateFiles($presenterName, $layout = 'layout');


	/**
	 * Formats view template file names
	 * @param string $presenterName
	 * @param string $presenterView
	 * @return array
	 */
	public function formatTemplateFiles($presenterName, $presenterView);


	/**
	 * Formats component template file names
	 * @param string $presenterName
	 * @param string $presenterView
	 * @param string $controlClass
	 * @return array
	 */
	public function formatComponentTemplateFiles($presenterName, $presenterView, $controlClass);


	/**
	 * Format FileTemplate Files
	 * @param string $template
	 * @return array
	 */
	public function formatFileTemplateFiles($template);


	/**
	 * @param IFilesFormatterLogger $logger
	 * @return TemplateFilesFormatter
	 */
	public function setLogger(IFilesFormatterLogger $logger);


	/**
	 * @param string $dir
	 * @param int $priority
	 * @return TemplateFilesFormatter
	 */
	public function addDir($dir, $priority = 5);
}

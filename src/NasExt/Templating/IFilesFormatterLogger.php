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
 * IFilesFormatterLogger
 *
 * @author Patrik Votoček
 * @author Dusan Hudak
 */
interface IFilesFormatterLogger
{
    /**
	 * @param string $name
	 * @param array $files
	 * @param bool $onTop
	 */
    public function logFiles($name, array $files, $onTop = false);
}

<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 *
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Templating\DI;

use NasExt\Templating\InvalidArgumentException;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;

if (!class_exists('Nette\DI\CompilerExtension')) {
	class_alias('Nette\Config\CompilerExtension', 'Nette\DI\CompilerExtension');
	class_alias('Nette\Config\Compiler', 'Nette\DI\Compiler');
}

if (isset(\Nette\Loaders\NetteLoader::getInstance()->renamed['Nette\Configurator']) || !class_exists('Nette\Configurator')) {
	unset(\Nette\Loaders\NetteLoader::getInstance()->renamed['Nette\Configurator']);
	class_alias('Nette\Config\Configurator', 'Nette\Configurator');
}

/**
 * TemplatingExtension
 *
 * @author Dusan Hudak
 */
class TemplatingExtension extends CompilerExtension
{

	/** @var array */
	public $defaults = array(
		'directories' => array('%appDir%'),
		'formatter' => FALSE,
		'debugger' => '%debugMode%',
	);


	public function beforeCompile()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$formatter = $config['formatter'];

		if ($formatter) {
			$formatter = $builder->getDefinition(substr($formatter, 1));
			$class = $formatter->class ? : $formatter->factory->entity;

			if (!in_array('NasExt\Templating\ITemplateFilesFormatter', class_implements($class))) {
				throw new InvalidArgumentException("Service '$formatter->class' must implement  NasExt\Templating\ITemplateFilesFormatter.");
			}
		} else {
			$definition = $builder->addDefinition($this->prefix('templateFilesFormatter'));
			$formatter = $definition->setClass('NasExt\Templating\TemplateFilesFormatter');
		}

		foreach ($config['directories'] as $key => $dir) {
			$formatter->addSetup('addDir', array($dir, $key + 1));
		}

		if ($config['debugger']) {
			$formatter->addSetup('NasExt\Templating\Diagnostics\FilesPanel::register(?)', array('@self'));
		}
	}


	/**
	 * @param Configurator $configurator
	 */
	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function (Configurator $config, Compiler $compiler) {
			$compiler->addExtension('templateFilesFormatter', new TemplatingExtension());
		};
	}
}

NasExt/Templating
===========================

NasExt/Templating extension  is  to search template files for the layout, view, component  templates and single fileTemplates in Nette Framework.

Requirements
------------

NasExt/GoogleCharts requires PHP 5.3.2 or higher.

- [Nette Framework 2.0.x](https://github.com/nette/nette)

Installation
------------

The best way to install NasExt/Templating is using  [Composer](http://getcomposer.org/):

```sh
$ composer require nasext/templating:@dev
```

Enable the extension using your neon config.

```yml
extensions:
	nasext.templating: NasExt\Templating\DI\TemplatingExtension
```

Configuration
```yml
services:
	myTemplateFilesFormatter: App\MyTemplateFilesFormatter

nasext.templating:
	debugger: TRUE
	directories: [%myThemeDir%, %secondThemeDir%]
	formatter: @myTemplateFilesFormatter
```

- debugger: enable debugBar, default is %debugMode%
- directories: array of directories where formatter find templates, default is %appDir%
- formatter:
If you do not want to use the default formatter, you can add your own formateur like [this](https://gist.github.com/duskohu/7364973). Suffice formatter register as a service and add to parameter formatter of config.
If you set a custom formatter do not forget to implement   NasExt\Templating\ITemplateFilesFormatter.

## Use
First step is to inject services TemplateFilesFormatter
```php
/** @var \NasExt\Templating\ITemplateFilesFormatter */
protected $templateFilesFormatter;

/**
 * INJECT TemplateFilesFormatter
 * @param \NasExt\Templating\ITemplateFilesFormatter $templateFilesFormatter
 */
public function injectTemplateFilesFormatter(\NasExt\Templating\ITemplateFilesFormatter $templateFilesFormatter)
{
	$this->templateFilesFormatter = $templateFilesFormatter;
}
```

###Formats layout template file names

For formatting the layout presenter suffices to rewrite formatLayoutTemplateFiles method and call the method: formatLayoutTemplateFiles of TemplateFilesFormatter
```php
/**
 * Formats layout template file names.
 * @return array
 */
public function formatLayoutTemplateFiles()
{
	parent::formatLayoutTemplateFiles();
	$formatter = $this->templateFilesFormatter->formatLayoutTemplateFiles($this->name, $this->layout);
	return $formatter->getFiles();
}
```

###Formats view template file names

To format the presenter view suffices to rewrite formatTemplateFiles method and call the method: formatTemplateFiles of TemplateFilesFormatter
```php
/**
 * Formats view template file names.
 * @return array
 */
public function formatTemplateFiles()
{
	$formatter = $this->templateFilesFormatter->formatTemplateFiles($this->name, $this->view);
	return $formatter->getFiles();
}
```

###Formats component template file names

For formatting template components suffices to call a method: formatComponentTemplateFiles of TemplateFilesFormatter.
```php
$reflection = $this->getReflection();
$name = $reflection->getShortName();

$formatter = $this->templateFilesFormatter->formatComponentTemplateFiles($this->presenter->name, $this->presenter->view, $name);
$fileTemplate = $formatter->getTemplateFile();
```

###Format FileTemplate Files

Also you can search fileTemplates used for this purpose method: formatFileTemplateFiles of TemplateFilesFormatter
```php
$formatter = $this->templateFilesFormatter->formatFileTemplateFiles('emails/newUser.latte');
$fileTemplate = $formatter->getTemplateFile();
```

All method return NasExt\Templating\Formater so you can add additional file/files.
####Example - BaseControl
```php
class BaseControl extends Nette\Application\UI\Control
{
	/** @var string */
	protected $templateFile;

	/**
	 * Create template for all controls
	 * @param  string|NULL
	 * @return ITemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->setFile($this->getTemplateFilePath());
		return $template;
	}

	/**
	 * @return string
	 */
	protected function getTemplateFilePath()
	{
		$reflection = $this->getReflection();
		$dir = dirname($reflection->getFileName());
		$name = $reflection->getShortName();
		$basFile = $dir . DIRECTORY_SEPARATOR . $name . '.latte';


		if ($this->templateFile) {
			$file = $templateFile = $this->templateFile;
		} else {
			$file = $basFile;
		}


		if ($this->templateFilesFormatter) {
			// Format component TemplateFiles
			$formatter = $this->templateFilesFormatter->formatComponentTemplateFiles($this->presenter->name, $this->presenter->view, $name);

			// Add more files to the end of the list
			$formatter->addFile($basFile);
			if ($this->templateFile) {
				// Add more files to the beginning of the list
				$formatter->addFile($this->templateFile, TRUE);
			}

			// Get the first active file from list
			$file = $formatter->getTemplateFile();
		}

		return $file;
	}
}
```

-----

Repository [http://github.com/nasext/templating](http://github.com/nasext/templating).
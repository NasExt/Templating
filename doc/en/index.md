NasExt/Templating
===========================

This lib is help for find layout, view, component templates and fileTemplates in Nette Framework.

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
	debugger :TRUE
	dirList: [%myThemeDir%, %secondThemeDir%]
	formatter: @myTemplateFilesFormatter
```

- debugger: enable debugBar, default is %debugMode%
- dirList: array of directories where formatter find templates, default is %appDir%
- formatter: set custom formatter implements NasExt\Templating\ITemplateFilesFormatter like [this](https://gist.github.com/duskohu/7364973) else enable default formatter

## Use
Inject service
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

Formats layout template file names
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

Formats layout template file names
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

Formats component template file names
```php
$reflection = $this->getReflection();
$name = $reflection->getShortName();

$formatter = $this->templateFilesFormatter->formatComponentTemplateFiles($this->presenter->name, $this->presenter->view, $name);
$files = $formatter->getFiles();
foreach ($files as $file) {
	if (is_file($file)) {
		$template->setFile($file);
		break;
	}
}
```

Format FileTemplate Files
```php
$formatter = $this->templateFilesFormatter->formatFileTemplateFiles('components/emails/newUser.latte');
$files = $formatter->getFiles();
foreach ($files as $file) {
	if (is_file($file)) {
		$fileTemplate = $file;
		break;
	}
}
```

All method return NasExt\Templating\Formater so you can add additional file/files.

-----

Repository [http://github.com/nasext/templating](http://github.com/nasext/templating).
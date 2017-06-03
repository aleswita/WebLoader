# Web Loader
Web Loader for Nette Framework.

[![Build Status](https://travis-ci.org/aleswita/WebLoader.svg?branch=master)](https://travis-ci.org/aleswita/WebLoader)
[![Coverage Status](https://coveralls.io/repos/github/aleswita/WebLoader/badge.svg?branch=master)](https://coveralls.io/github/aleswita/WebLoader?branch=master)

## Installation
The best way to install AlesWita/WebLoader is using [Composer](http://getcomposer.org/):
```sh
# For PHP 7.1 and Nette Framework 2.4/3.0
$ composer require aleswita/webloader:dev-master
```


## Usage

#### Config
```neon
extensions:
	webloader: AlesWita\Components\WebLoader\Extension

webloader:
	expiration: 1 day # for regenerate links, sometimes helpful if some outdated browsers can not load new version of file
	files:
		-
			originalFile: %appDir%/../node_modules/normalize.css/normalize.css
			tag: css
			namespace: [Front, Admin]
		-
			originalFile: %appDir%/../node_modules/bootstrap/dist/css/bootstrap.min.css
			tag: css
			namespace: [Front, Admin]
		-
			originalFile: %appDir%/../node_modules/font-awesome/css/font-awesome.min.css
			tag: css
			namespace: [Front]
		-
			originalFile: %appDir%/../node_modules/jquery/dist/jquery.min.js
			tag: js
			namespace: [Front, Admin]
		-
			originalFile: %appDir%/../node_modules/tether/dist/js/tether.min.js
			tag: js
			namespace: [Front, Admin]
		-
			originalFile: %appDir%/../node_modules/bootstrap/dist/js/bootstrap.min.js
			tag: js
			namespace: [Front, Admin]
		-
			originalFile: %appDir%/../node_modules/nette-forms/src/assets/netteForms.min.js
			tag: js
			namespace: [Front, Admin]
	folders:
		-
			originalFolder: %appDir%/../node_modules/font-awesome/fonts
			tag: other
			namespace: [Front]
			folder: fonts
```


#### Presenter
```php
use AlesWita;
use Nette\Application;


abstract class BasePresenter extends Application\UI\Presenter
{
	...
	...

	/**
	 * @return AlesWita\Components\WebLoader\Loader\Css
	 */
	protected function createComponentCss(): AlesWita\Components\WebLoader\Loader\Css {
		return $this->webLoader->getCssLoader("Front");
	}

	/**
	 * @return AlesWita\Components\WebLoader\Loader\Js
	 */
	protected function createComponentJs(): AlesWita\Components\WebLoader\Loader\Js {
		return $this->webLoader->getJsLoader("Front");
	}
}
```

#### Template
```html
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  {control css}
  {control js}
 </head>
 ...
 ...
```

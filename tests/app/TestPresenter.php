<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader\Tests\App;

use AlesWita;
use Nette;


final class TestPresenter extends Nette\Application\UI\Presenter
{
	/** @var AlesWita\Components\WebLoader\Factory @inject */
	public $webLoader;

	/**
	 * @return void
	 */
	public function actionOne(): void {
		$this->setView("cssLoader");
	}

	/**
	 * @return void
	 */
	public function actionTwo(): void {
		$this->setView("jsLoader");
	}

	/**
	 * @return AlesWita\Components\WebLoader\Loader\Css
	 */
	protected function createComponentCss(): AlesWita\Components\WebLoader\Loader\Css {
		return $this->webLoader->getCssLoader();
	}

	/**
	 * @return AlesWita\Components\WebLoader\Loader\Js
	 */
	protected function createComponentJs(): AlesWita\Components\WebLoader\Loader\Js {
		return $this->webLoader->getJsLoader();
	}

	/**
	 * @param  Nette\Application\IResponse
	 * @return void
	 */
	protected function shutdown($response): void {
		parent::shutdown($response);
		$this->webLoader->getCache()->clean([Nette\Caching\Cache::TAGS => [AlesWita\Components\WebLoader\Factory::CACHE_TAG]]);
	}
}

<?php

/**
 * This file is part of the AlesWita\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\WebLoader\Tests\App\Presenters;

use AlesWita;
use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
final class GettersPresenter extends Nette\Application\UI\Presenter
{
	/** @var AlesWita\WebLoader\Factory @inject */
	public $webLoader;

	/**
	 * @return void
	 */
	public function actionOne(): void {
		$this->setView("default");
	}

	/**
	 * @return AlesWita\WebLoader\Loader\Css
	 */
	protected function createComponentCss(): AlesWita\WebLoader\Loader\Css {
		return $this->webLoader->getCssLoader();
	}

	/**
	 * @return AlesWita\WebLoader\Loader\Js
	 */
	protected function createComponentJs(): AlesWita\WebLoader\Loader\Js {
		return $this->webLoader->getJsLoader();
	}

	/**
	 * @param Nette\Application\IResponse
	 * @return void
	 */
	protected function shutdown($response): void {
		parent::shutdown($response);
		$this->webLoader->getCache()->clean([Nette\Caching\Cache::TAGS => [$this->webLoader->getCacheTag()]]);
	}
}

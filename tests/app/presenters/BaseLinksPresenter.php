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
final class BaseLinksPresenter extends Nette\Application\UI\Presenter
{
	/** @var AlesWita\WebLoader\Factory @inject */
	public $webLoader;


	/**
	 * @return void
	 */
	public function actionOne(): void
	{
		$this->setView('cssLoader');
	}


	/**
	 * @return void
	 */
	public function actionTwo(): void
	{
		$this->setView('jsLoader');
	}


	/**
	 * @return void
	 */
	public function actionThree(): void
	{
		$this->setView('tagLoader');
	}


	/**
	 * @return void
	 */
	public function actionFour(): void
	{
		$this->setView('tagLoader');
	}


	/**
	 * @return AlesWita\WebLoader\Loader\Css
	 */
	protected function createComponentCss(): AlesWita\WebLoader\Loader\Css
	{
		return $this->webLoader->getCssLoader();
	}


	/**
	 * @return AlesWita\WebLoader\Loader\Js
	 */
	protected function createComponentJs(): AlesWita\WebLoader\Loader\Js
	{
		return $this->webLoader->getJsLoader();
	}


	/**
	 * @return AlesWita\WebLoader\Loader\Tag
	 */
	protected function createComponentTag(): AlesWita\WebLoader\Loader\Tag
	{
		return $this->webLoader->getTagLoader();
	}


	/**
	 * @param Nette\Application\IResponse
	 * @return void
	 */
	protected function shutdown($response): void
	{
		parent::shutdown($response);
		$this->webLoader->getCache()->clean([Nette\Caching\Cache::TAGS => [$this->webLoader->getCacheTag()]]);
	}
}

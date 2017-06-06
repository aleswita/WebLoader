<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader\Tests\App\Presenters;

use AlesWita;
use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
final class DebugModePresenter extends Nette\Application\UI\Presenter
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
	 * @return AlesWita\Components\WebLoader\Loader\Css
	 */
	protected function createComponentCss(): AlesWita\Components\WebLoader\Loader\Css {
		return $this->webLoader->getCssLoader();
	}
}

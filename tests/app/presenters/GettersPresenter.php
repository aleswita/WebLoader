<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader\Tests\App\Presenters;

use AlesWita;
use Nette;


final class GettersPresenter extends Nette\Application\UI\Presenter
{
	/** @var AlesWita\Components\WebLoader\Factory @inject */
	public $webLoader;
}

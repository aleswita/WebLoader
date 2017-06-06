<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader\Loader;

use AlesWita;
use Nette;
use Nette\Application;


/**
 * @author Aleš Wita
 * @license MIT
 */
interface ILoader
{
	/**
	 * @param array
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	function setFiles(array $files): AlesWita\Components\WebLoader\Loader\ILoader;

	/**
	 * @param string
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	function setNamespace(string $namespace): AlesWita\Components\WebLoader\Loader\ILoader;

	/**
	 * @param Nette\Caching\Cache
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	public function setCache(Nette\Caching\Cache $cache): AlesWita\Components\WebLoader\Loader\ILoader;

	/**
	 * @param string
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	public function setCacheTag(string $tag): AlesWita\Components\WebLoader\Loader\ILoader;

	/**
	 * @param string|NULL
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	public function setExpiration(?string $expiration): AlesWita\Components\WebLoader\Loader\ILoader;

	/**
	 * @return void
	 */
	function render(): void;
}

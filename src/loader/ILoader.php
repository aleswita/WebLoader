<?php

/**
 * This file is part of the AlesWita\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\WebLoader\Loader;

use AlesWita;
use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
interface ILoader
{
	/**
	 * @param array
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	function setFiles(array $files): AlesWita\WebLoader\Loader\ILoader;

	/**
	 * @param string
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	function setNamespace(string $namespace): AlesWita\WebLoader\Loader\ILoader;

	/**
	 * @param Nette\Caching\Cache
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setCache(Nette\Caching\Cache $cache): AlesWita\WebLoader\Loader\ILoader;

	/**
	 * @param string
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setCacheTag(string $tag): AlesWita\WebLoader\Loader\ILoader;

	/**
	 * @param string|NULL
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setExpiration(?string $expiration): AlesWita\WebLoader\Loader\ILoader;

	/**
	 * @return void
	 */
	function render(): void;
}

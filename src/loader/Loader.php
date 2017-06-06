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
use Nette\Utils;


/**
 * @author Ales Wita
 * @license MIT
 */
abstract class Loader extends Application\UI\Control implements ILoader
{
	/** @var array */
	protected $files = [];

	/** @var string */
	protected $namespace;

	/** @var Nette\Caching\Cache */
	protected $cache;

	/** @var string */
	protected $expiration;

	/** ******************** */

	/**
	 * @param array
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	abstract function setFiles(array $files): AlesWita\Components\WebLoader\Loader\ILoader;

	/**
	 * @param string
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	public function setNamespace(string $namespace): AlesWita\Components\WebLoader\Loader\ILoader {
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * @param Nette\Caching\Cache
	 * @param string
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	public function setCache(Nette\Caching\Cache $cache): AlesWita\Components\WebLoader\Loader\ILoader {
		$this->cache = $cache;
		return $this;
	}

	/**
	 * @param string|NULL
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	public function setExpiration(?string $expiration): AlesWita\Components\WebLoader\Loader\ILoader {
		$this->expiration = $expiration;
		return $this;
	}

	/** ******************** */
}

<?php

/**
 * This file is part of the AlesWita\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\WebLoader\Loader;

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
	protected $cacheTag;

	/** @var string */
	protected $expiration;

	/** ******************** */

	/**
	 * @param array
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	abstract function setFiles(array $files): AlesWita\WebLoader\Loader\ILoader;

	/**
	 * @param string
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setNamespace(string $namespace): AlesWita\WebLoader\Loader\ILoader {
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * @param Nette\Caching\Cache
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setCache(Nette\Caching\Cache $cache): AlesWita\WebLoader\Loader\ILoader {
		$this->cache = $cache;
		return $this;
	}

	/**
	 * @param string
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setCacheTag(string $tag): AlesWita\WebLoader\Loader\ILoader {
		$this->cacheTag = $tag;
		return $this;
	}

	/**
	 * @param string|NULL
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setExpiration(?string $expiration): AlesWita\WebLoader\Loader\ILoader {
		$this->expiration = $expiration;
		return $this;
	}

	/** ******************** */
}

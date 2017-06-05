<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader;

use AlesWita;
use Nette;
use Nette\Caching;
use Nette\Utils;


/**
 * @author Ales Wita
 * @license MIT
 */
class Factory
{
	/** tags contants */
	const FILE_TAG_CSS = "css";
	const FILE_TAG_JS = "js";
	const FILE_TAG_OTHER = "other";

	/** folder constants */
	const DEFAULT_FOLDER_CSS = "css";
	const DEFAULT_FOLDER_JS = "js";

	/** cache constants */
	const CACHE_DEFAULT_NAMESPACE = "Web.Loader";
	const CACHE_TAG = "Web.Loader";

	const DEFAULT_NAMESPACE = "default";

	/** @var string */
	private $wwwDir;

	/** @var bool */
	private $debugMode;

	/** @var bool */
	private $productionMode;

	/** @var string */
	private $uniqueId;

	/** @var Nette\Caching\Cache */
	private $cache;

	/** @var Nette\Caching\IStorage */
	private $cacheStorage;

	/** @var string */
	private $cacheNamespace = self::CACHE_DEFAULT_NAMESPACE;

	/** @var Nette\Http\Request */
	private $httpRequest;

	/** @var string */
	private $expiration;

	/** @var array */
	private $cssFiles = [];

	/** @var array */
	private $jsFiles = [];

	/** @var array */
	private $otherFiles = [];

	/** ******************** */

	/**
	 * @param string
	 * @return self
	 */
	public function setWwwDir(string $wwwDir): self {
		$this->wwwDir = $wwwDir;
		return $this;
	}

	/**
	 * @param bool
	 * @return self
	 */
	public function setDebugMode(bool $debugMode): self {
		$this->debugMode = $debugMode;
		return $this;
	}

	/**
	 * @param bool
	 * @return self
	 */
	public function setProductionMode(bool $productionMode): self {
		$this->productionMode = $productionMode;
		return $this;
	}

	/**
	 * @param string
	 * @return self
	 */
	public function setUniqueId(string $uniqueId): self {
		$this->uniqueId = $uniqueId;
		return $this;
	}

	/**
	 * @param Nette\Caching\IStorage
	 * @return self
	 */
	public function setCacheStorage(Nette\Caching\IStorage $fileStorage): self {
		$this->cacheStorage = $fileStorage;
		return $this;
	}

	/**
	 * @param string
	 * @return self
	 */
	public function setCacheNamespace(string $namespace): self {
		$this->cacheNamespace = $namespace;
		return $this;
	}

	/**
	 * @param Nette\Http\IRequest
	 * @return self
	 */
	public function setHttpRequest(Nette\Http\IRequest $httpRequest): self {
		$this->httpRequest = $httpRequest;
		return $this;
	}

	/**
	 * @param string
	 * @return self
	 */
	public function setExpiration(string $expiration): self {
		$this->expiration = $expiration;
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 */
	public function addCssFile(array $fileSettings): self {
		$this->cssFiles[] = $fileSettings;
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 */
	public function addJsFile(array $fileSettings): self {
		$this->jsFiles[] = $fileSettings;
		return $this;
	}

	/**
	 * @param array
	 * @return self
	 */
	public function addOtherFile(array $fileSettings): self {
		$this->otherFiles[] = $fileSettings;
		return $this;
	}

	/** ******************** */

	/**
	 * @return string|NULL
	 */
	public function getExpiration(): ?string {
		return $this->expiration;
	}

	/**
	 * @return string
	 */
	public function getWwwDir(): string {
		return $this->wwwDir;
	}

	/**
	 * @return bool
	 */
	public function getDebugMode(): bool {
		return $this->debugMode;
	}

	/**
	 * @return bool
	 */
	public function getProductionMode(): bool {
		return $this->productionMode;
	}

	/**
	 * @return string
	 */
	public function getUniqueId(): string {
		return $this->uniqueId;
	}

	/**
	 * @return Nette\Caching\Cache
	 */
	public function getCache(): Nette\Caching\Cache {
		if ($this->cache === NULL) {
			$this->cache = new Caching\Cache($this->cacheStorage, $this->cacheNamespace);
		}
		return $this->cache;
	}

	/**
	 * @return string
	 */
	public function getCacheNamespace(): string {
		return $this->cacheNamespace;
	}

	/**
	 * @return Nette\Http\IRequest
	 */
	public function getHttpRequest(): Nette\Http\IRequest {
		return $this->httpRequest;
	}

	/**
	 * @return array
	 */
	public function getCssFiles(): array {
		return $this->cssFiles;
	}

	/**
	 * @return array
	 */
	public function getJsFiles(): array {
		return $this->jsFiles;
	}

	/**
	 * @return array
	 */
	public function getOtherFiles(): array {
		return $this->otherFiles;
	}

	/**
	 * @param string
	 * @return AlesWita\Components\WebLoader\Css
	 */
	public function getCssLoader(string $namespace = self::DEFAULT_NAMESPACE): AlesWita\Components\WebLoader\Loader\Css {
		$cssLoader = new Loader\Css;

		$cssLoader->setFiles($this->prepare($namespace))
			->setNamespace($namespace)
			->setCache($this->getCache(), $this->expiration);

		return $cssLoader;
	}

	/**
	 * @param string
	 * @return AlesWita\Components\WebLoader\Js
	 */
	public function getJsLoader(string $namespace = self::DEFAULT_NAMESPACE): AlesWita\Components\WebLoader\Loader\Js {
		$jsLoader = new Loader\Js;

		$jsLoader->setFiles($this->prepare($namespace))
			->setNamespace($namespace)
			->setCache($this->getCache());

		return $jsLoader;
	}

	/**
	 * @return string
	 */
	private function getBasePath(): string {
		// code snippet from Nette\Bridges\ApplicationLatte\TemplateFactory
		$foo = rtrim($this->httpRequest->getUrl()->getBaseUrl(), "/");
		$foo = preg_replace("#https?://[^/]+#A", "", $foo);

		return $foo;
	}

	/**
	 * @param string
	 * @return self
	 */
	private function prepare(string $namespace): array {
		if ($this->debugMode) {
			// invalidate cache, if some changes in container (only for debug mode, production no need)
			if ($this->uniqueId !== $this->getCache()->load("uniqueId")) {
				$this->getCache()->clean([Caching\Cache::TAGS => [self::CACHE_TAG]]);
				$this->getCache()->save("uniqueId", function (& $dp) use ($namespace): string {
					$dp = [Caching\Cache::TAGS => [self::CACHE_TAG]];
					return $this->uniqueId;
				});
			}

			// checking hash with original file (only for debug mode, production no need)
			if ($this->prepareFiles($namespace)) {
				$this->getCache()->clean([Caching\Cache::TAGS => [self::CACHE_TAG]]);
			}
		}

		return $this->getCache()->load("namespace-{$namespace}", function (& $dp) use ($namespace): array {
			$dp = [Caching\Cache::TAGS => [self::CACHE_TAG]];
			$output = [];
			$basePath = $this->getBasePath();

			foreach ($this->cssFiles as $file) {
				if (in_array($namespace, $file["namespace"], TRUE)) {
					$output[self::FILE_TAG_CSS][] = "{$basePath}/{$file["folder"]}/{$file["baseName"]}";
				}
			}

			foreach ($this->jsFiles as $file) {
				if (in_array($namespace, $file["namespace"], TRUE)) {
					$output[self::FILE_TAG_JS][] = "{$basePath}/{$file["folder"]}/{$file["baseName"]}";
				}
			}

			foreach ($this->otherFiles as $file) {
				if (in_array($namespace, $file["namespace"], TRUE)) {
					$output[self::FILE_TAG_OTHER][] = "{$basePath}/{$file["folder"]}/{$file["baseName"]}";
				}
			}

			$this->prepareFiles($namespace);
			return $output;
		});
	}

	/**
	 * @param string
	 * @return bool
	 */
	private function prepareFiles(?string $namespace = NULL): bool {
		$isAnyChanges = FALSE;

		foreach ([$this->cssFiles, $this->jsFiles, $this->otherFiles] as $files) {
			foreach ($files as $file) {
				if ($namespace === NULL || in_array($namespace, $file["namespace"], TRUE)) {
					if (!file_exists($file["file"]) || (md5_file($file["file"]) !== $file["hash"] || ($this->debugMode && md5_file($file["file"]) !== md5_file($file["originalFile"])))) {
						Utils\FileSystem::copy($file["originalFile"], $file["file"]);
						$isAnyChanges = TRUE;
					}
				}
			}
		}
		return $isAnyChanges;
	}
}

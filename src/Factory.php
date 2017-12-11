<?php

/**
 * This file is part of the AlesWita\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\WebLoader;

use AlesWita;
use Nette;


/**
 * @property-read string $wwwDir
 * @property-read bool $debugMode
 * @property-read bool $productionMode
 * @property-read string $uniqueId
 * @property-read Nette\Caching\Cache $cache
 * @property-read string $cacheNamespace
 * @property-read string $cacheTag
 * @property-read Nette\Http\Request $httpRequest
 * @property-read string $expiration
 * @property-read array $cssFiles
 * @property-read array $jsFiles
 * @property-read array $otherFiles
 * @property-read array $htmlTags
 *
 * @author Ales Wita
 * @license MIT
 */
class Factory
{
	use Nette\SmartObject;

	/** tags contants */
	public const TAG_FILE_CSS = 'css';

	public const TAG_FILE_JS = 'js';

	public const TAG_FILE_OTHER = 'other';

	public const TAG_HTML = 'html';

	/** back compatibility */
	public const FILE_TAG_CSS = self::TAG_FILE_CSS;

	public const FILE_TAG_JS = self::TAG_FILE_JS;

	public const FILE_TAG_OTHER = self::TAG_FILE_OTHER;

	/** folder constants */
	public const DEFAULT_FOLDER_CSS = 'css';

	public const DEFAULT_FOLDER_JS = 'js';

	/** cache constants */
	public const CACHE_DEFAULT_NAMESPACE = 'Web.Loader';

	public const CACHE_DEFAULT_TAG = 'Web.Loader';

	public const DEFAULT_NAMESPACE = 'default';

	/** @var string */
	private $expiration;

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

	/** @var string */
	private $cacheTag = self::CACHE_DEFAULT_TAG;

	/** @var Nette\Http\Request */
	private $httpRequest;

	/** @var array */
	private $cssFiles = [];

	/** @var array */
	private $jsFiles = [];

	/** @var array */
	private $otherFiles = [];

	/** @var array */
	private $htmlTags = [];


	/**
	 * @param string
	 * @return self
	 */
	public function setWwwDir(string $wwwDir): self
	{
		$this->wwwDir = $wwwDir;
		return $this;
	}


	/**
	 * @param bool
	 * @return self
	 */
	public function setDebugMode(bool $debugMode): self
	{
		$this->debugMode = $debugMode;
		return $this;
	}


	/**
	 * @param bool
	 * @return self
	 */
	public function setProductionMode(bool $productionMode): self
	{
		$this->productionMode = $productionMode;
		return $this;
	}


	/**
	 * @param string
	 * @return self
	 */
	public function setUniqueId(string $uniqueId): self
	{
		$this->uniqueId = $uniqueId;
		return $this;
	}


	/**
	 * @param Nette\Caching\IStorage
	 * @return self
	 */
	public function setCacheStorage(Nette\Caching\IStorage $fileStorage): self
	{
		$this->cacheStorage = $fileStorage;
		return $this;
	}


	/**
	 * @param string
	 * @return self
	 */
	public function setCacheNamespace(string $namespace): self
	{
		$this->cacheNamespace = $namespace;
		return $this;
	}


	/**
	 * @param string
	 * @return self
	 */
	public function setCacheTag(string $tag): self
	{
		$this->cacheTag = $tag;
		return $this;
	}


	/**
	 * @param Nette\Http\IRequest
	 * @return self
	 */
	public function setHttpRequest(Nette\Http\IRequest $httpRequest): self
	{
		$this->httpRequest = $httpRequest;
		return $this;
	}


	/**
	 * @param string
	 * @return self
	 */
	public function setExpiration(string $expiration): self
	{
		$this->expiration = $expiration;
		return $this;
	}


	/**
	 * @param array
	 * @return self
	 */
	public function addCssFile(array $fileSettings): self
	{
		$this->cssFiles[] = $fileSettings;
		return $this;
	}


	/**
	 * @param array
	 * @return self
	 */
	public function addJsFile(array $fileSettings): self
	{
		$this->jsFiles[] = $fileSettings;
		return $this;
	}


	/**
	 * @param array
	 * @return self
	 */
	public function addOtherFile(array $fileSettings): self
	{
		$this->otherFiles[] = $fileSettings;
		return $this;
	}


	/**
	 * @param Nette\Utils\Html
	 * @return self
	 */
	public function addHtmlTag(array $htmlTagSettings): self
	{
		$this->htmlTags[] = $htmlTagSettings;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getWwwDir(): string
	{
		return $this->wwwDir;
	}


	/**
	 * @return bool
	 */
	public function getDebugMode(): bool
	{
		return $this->debugMode;
	}


	/**
	 * @return bool
	 */
	public function getProductionMode(): bool
	{
		return $this->productionMode;
	}


	/**
	 * @return string
	 */
	public function getUniqueId(): string
	{
		return $this->uniqueId;
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function getCache(): Nette\Caching\Cache
	{
		if ($this->cache === null) {
			$this->cache = new Nette\Caching\Cache($this->cacheStorage, $this->cacheNamespace);
		}
		return $this->cache;
	}


	/**
	 * @return string
	 */
	public function getCacheNamespace(): string
	{
		return $this->cacheNamespace;
	}


	/**
	 * @return string
	 */
	public function getCacheTag(): string
	{
		return $this->cacheTag;
	}


	/**
	 * @return Nette\Http\IRequest
	 */
	public function getHttpRequest(): Nette\Http\IRequest
	{
		return $this->httpRequest;
	}


	/**
	 * @return string|null
	 */
	public function getExpiration(): ?string
	{
		return $this->expiration;
	}


	/**
	 * @return array
	 */
	public function getCssFiles(): array
	{
		return $this->cssFiles;
	}


	/**
	 * @return array
	 */
	public function getJsFiles(): array
	{
		return $this->jsFiles;
	}


	/**
	 * @return array
	 */
	public function getOtherFiles(): array
	{
		return $this->otherFiles;
	}


	/**
	 * @return array
	 */
	public function getHtmlTags(): array
	{
		return $this->htmlTags;
	}


	/**
	 * @param string
	 * @return AlesWita\WebLoader\Css
	 */
	public function getCssLoader(string $namespace = self::DEFAULT_NAMESPACE): AlesWita\WebLoader\Loader\Css
	{
		$cssLoader = new Loader\Css;

		$cssLoader->setFiles($this->prepare($namespace))
			->setNamespace($namespace)
			->setCache($this->getCache())
			->setCacheTag($this->cacheTag)
			->setExpiration($this->expiration);

		return $cssLoader;
	}


	/**
	 * @param string
	 * @return AlesWita\WebLoader\Js
	 */
	public function getJsLoader(string $namespace = self::DEFAULT_NAMESPACE): AlesWita\WebLoader\Loader\Js
	{
		$jsLoader = new Loader\Js;

		$jsLoader->setFiles($this->prepare($namespace))
			->setNamespace($namespace)
			->setCache($this->getCache())
			->setCacheTag($this->cacheTag)
			->setExpiration($this->expiration);

		return $jsLoader;
	}


	/**
	 * @param string
	 * @return AlesWita\WebLoader\Tag
	 */
	public function getTagLoader(string $namespace = self::DEFAULT_NAMESPACE): AlesWita\WebLoader\Loader\Tag
	{
		$tagLoader = new Loader\Tag;

		$tagLoader->setFiles($this->prepare($namespace))
			->setNamespace($namespace)
			->setCache($this->getCache())
			->setCacheTag($this->cacheTag)
			->setExpiration($this->expiration);

		return $tagLoader;
	}


	/**
	 * @return string
	 */
	private function getBasePath(): string
	{
		// code snippet from Nette\Bridges\ApplicationLatte\TemplateFactory
		$foo = rtrim($this->httpRequest->getUrl()->getBaseUrl(), '/');
		$foo = preg_replace('#https?://[^/]+#A', '', $foo);

		return $foo;
	}


	/**
	 * @param string
	 * @return self
	 */
	private function prepare(string $namespace): array
	{
		if ($this->debugMode) {
			// invalidate cache, if some changes in container (only for debug mode, production no need)
			if ($this->uniqueId !== $this->getCache()->load('uniqueId')) {
				$this->getCache()->clean([Nette\Caching\Cache::ALL => true]);
				$this->getCache()->save('uniqueId', function (&$dp): string {
					return $this->uniqueId;
				});
			}

			// checking hash with original file (only for debug mode, production no need)
			if ($this->prepareFiles($namespace)) {
				$this->getCache()->clean([Nette\Caching\Cache::TAGS => [$this->cacheTag]]);
			}
		}

		return $this->getCache()->load('namespace-' . $namespace, function (&$dp) use ($namespace): array {
			$dp = [Nette\Caching\Cache::TAGS => [$this->cacheTag]];
			$output = [];
			$basePath = $this->getBasePath();

			foreach ($this->cssFiles as $file) {
				if (in_array($namespace, $file['namespace'], true)) {
					$output[self::TAG_FILE_CSS][] = $basePath . '/' . $file['folder'] . '/' . $file['baseName'];
				}
			}

			foreach ($this->jsFiles as $file) {
				if (in_array($namespace, $file['namespace'], true)) {
					$output[self::TAG_FILE_JS][] = $basePath . '/' . $file['folder'] . '/' . $file['baseName'];
				}
			}

			foreach ($this->otherFiles as $file) {
				if (in_array($namespace, $file['namespace'], true)) {
					$output[self::TAG_FILE_OTHER][] = $basePath . '/' . $file['folder'] . '/' . $file['baseName'];
				}
			}

			foreach ($this->htmlTags as $tag) {
				if (in_array($namespace, $tag['namespace'], true)) {
					if ($tag['tag'] instanceof Nette\Utils\Html) {
						if ($tag['tag']->getSrc() !== null) {
							$src = Nette\Utils\Strings::trim($tag['tag']->getSrc(), '\\/');

							if (!Nette\Utils\Validators::isUrl($src)) {
								$tag['tag']->setSrc($basePath . '/' . $src);
							}
						} elseif ($tag['tag']->getHref() !== null) {
							$href = Nette\Utils\Strings::trim($tag['tag']->getHref(), '\\/');

							if (!Nette\Utils\Validators::isUrl($href)) {
								$tag['tag']->setHref($basePath . '/' . $href);
							}
						}
					}

					$output[self::TAG_HTML][] = $tag['tag'];
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
	private function prepareFiles(?string $namespace = null): bool
	{
		$isAnyChanges = false;

		foreach ([$this->cssFiles, $this->jsFiles, $this->otherFiles] as $files) {
			foreach ($files as $file) {
				if ($namespace === null || in_array($namespace, $file['namespace'], true)) {
					if (!file_exists($file['file']) || (md5_file($file['file']) !== $file['hash'] || ($this->debugMode && md5_file($file['file']) !== md5_file($file['originalFile'])))) {
						Nette\Utils\FileSystem::copy($file['originalFile'], $file['file']);
						$isAnyChanges = true;
					}
				}
			}
		}

		return $isAnyChanges;
	}
}

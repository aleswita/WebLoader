<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader\Loader;

use AlesWita;
use AlesWita\Components\WebLoader\Factory;
use Nette\Caching;
use Nette\Utils;


/**
 * @author Ales Wita
 * @license MIT
 */
class Js extends Loader
{
	/**
	 * @param array
	 * @return AlesWita\Components\WebLoader\Loader\ILoader
	 */
	public function setFiles(array $files): AlesWita\Components\WebLoader\Loader\ILoader {
		if (isset($files[Factory::FILE_TAG_JS])) {
			$this->files = $files[Factory::FILE_TAG_JS];
		} else {
			$this->files = [];
		}
		return $this;
	}

	/**
	 * @return void
	 */
	public function render(): void {
		echo $this->cache->load("namespace-{$this->namespace}-tag-" . Factory::FILE_TAG_JS, function (& $dp): string {
			$dp = [
				Caching\Cache::TAGS => [$this->cacheTag],
				Caching\Cache::EXPIRE => $this->expiration,
			];

			$dateTime = new Utils\DateTime();
			$main = Utils\Html::el();

			foreach ($this->files as $file) {
				$html = Utils\Html::el("script")
					->setSrc("{$file}?v=" . md5((string) $dateTime->getTimestamp()))
					->setType("text/javascript");

				$main->insert(NULL, $html);
			}

			return $main->render(0);
		});
	}
}

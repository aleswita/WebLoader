<?php

/**
 * This file is part of the AlesWita\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\WebLoader\Loader;

use AlesWita;
use AlesWita\WebLoader\Factory;
use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
class Tag extends Loader
{
	/**
	 * @param array
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setFiles(array $files): AlesWita\WebLoader\Loader\ILoader
	{
		$this->files = (isset($files[Factory::TAG_HTML]) ? $files[Factory::TAG_HTML] : []);
		return $this;
	}


	/**
	 * @return void
	 */
	public function render(): void
	{
		echo $this->cache->load('namespace-' . $this->namespace . '-tag-' . Factory::TAG_HTML, function (&$dp): string {
			$dp = [
				Nette\Caching\Cache::TAGS => [$this->cacheTag],
				Nette\Caching\Cache::EXPIRE => $this->expiration,
			];

			$dateTime = new Nette\Utils\DateTime();
			$main = Nette\Utils\Html::el();

			foreach ($this->files as $tag) {
				$main->insert(null, $tag);
			}

			return $main->render(1);
		});
	}
}

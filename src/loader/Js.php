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
class Js extends Loader
{
	/**
	 * @param array
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setFiles(array $files): AlesWita\WebLoader\Loader\ILoader
	{
		$this->files = (isset($files[Factory::FILE_TAG_JS]) ? $files[Factory::FILE_TAG_JS] : []);
		return $this;
	}


	/**
	 * @return void
	 */
	public function render(): void
	{
		echo $this->cache->load('namespace-' . $this->namespace . '-tag-' . Factory::FILE_TAG_JS, function (&$dp): string {
			$dp = [
				Nette\Caching\Cache::TAGS => [$this->cacheTag],
				Nette\Caching\Cache::EXPIRE => $this->expiration,
			];

			$dateTime = new Nette\Utils\DateTime();
			$main = Nette\Utils\Html::el();

			foreach ($this->files as $file) {
				$html = Nette\Utils\Html::el('script')
					->setSrc($file . '?v=' . md5((string) $dateTime->getTimestamp()))
					->setType('text/javascript');

				$main->insert(null, $html);
			}

			return $main->render(0);
		});
	}
}

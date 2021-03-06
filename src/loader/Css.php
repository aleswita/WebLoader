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
class Css extends Loader
{
	//use Nette\SmartObject;// fail tests - E_USER_DEPRECATED: AlesWita\WebLoader\Loader\Css::getReflection() is deprecated in /home/travis/build/aleswita/WebLoader/vendor/nette/application/src/Application/UI/Component.php:129

	/**
	 * @param array
	 * @return AlesWita\WebLoader\Loader\ILoader
	 */
	public function setFiles(array $files): AlesWita\WebLoader\Loader\ILoader
	{
		$this->files = (isset($files[Factory::TAG_FILE_CSS]) ? $files[Factory::TAG_FILE_CSS] : []);
		return $this;
	}


	/**
	 * @return void
	 */
	public function render(): void
	{
		echo $this->cache->load('namespace-' . $this->namespace . '-tag-' . Factory::TAG_FILE_CSS, function (&$dp): string {
			$dp = [
				Nette\Caching\Cache::TAGS => [$this->cacheTag],
				Nette\Caching\Cache::EXPIRE => $this->expiration,
			];

			$dateTime = new Nette\Utils\DateTime();
			$main = Nette\Utils\Html::el();

			foreach ($this->files as $file) {
				$html = Nette\Utils\Html::el('link')
					->setRel('stylesheet')
					->setHref($file . '?v=' . md5((string) $dateTime->getTimestamp()))
					->setType('text/css');

				$main->insert(null, $html);
			}

			return $main->render(1);
		});
	}
}

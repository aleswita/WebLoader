<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 *
 * @phpVersion 7.1.0
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader\Tests\Tests;

use AlesWita;
use Nette;
use Tester;

require_once __DIR__ . "/../bootstrap.php";
require_once __DIR__ . "/../app/presenters/BaseLinksPresenter.php";
require_once __DIR__ . "/../app/presenters/GettersPresenter.php";
require_once __DIR__ . "/../app/router/Router.php";


final class GettersTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/gettersTestOne.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Getters");

		Tester\Assert::same("1 WEEK", $presenter->webLoader->getExpiration());
		Tester\Assert::same(__DIR__, $presenter->webLoader->getWwwDir());
		Tester\Assert::false($presenter->webLoader->getDebugMode());
		Tester\Assert::true($presenter->webLoader->getProductionMode());
		Tester\Assert::same(13, strlen($presenter->webLoader->getUniqueId()));
		Tester\Assert::true($presenter->webLoader->getCache() instanceof Nette\Caching\Cache);
		Tester\Assert::same("foo", $presenter->webLoader->getCacheNamespace());
		Tester\Assert::true($presenter->webLoader->getHttpRequest() instanceof Nette\Http\IRequest);


		$cssFiles = $presenter->webLoader->getCssFiles();

		Tester\Assert::count(1, $cssFiles);
		Tester\Assert::count(7, $cssFiles[0]);
		Tester\Assert::true(array_key_exists("originalFile", $cssFiles[0]));
		Tester\Assert::same("css", $cssFiles[0]["tag"]);
		Tester\Assert::same("default", $cssFiles[0]["namespace"][0]);
		Tester\Assert::same("css.css", $cssFiles[0]["baseName"]);
		Tester\Assert::same("css", $cssFiles[0]["folder"]);
		Tester\Assert::true(array_key_exists("hash", $cssFiles[0]));
		Tester\Assert::true(array_key_exists("file", $cssFiles[0]));


		$jsFiles = $presenter->webLoader->getJsFiles();

		Tester\Assert::count(1, $jsFiles);
		Tester\Assert::count(7, $jsFiles[0]);
		Tester\Assert::true(array_key_exists("originalFile", $jsFiles[0]));
		Tester\Assert::same("js", $jsFiles[0]["tag"]);
		Tester\Assert::same("default", $jsFiles[0]["namespace"][0]);
		Tester\Assert::same("js.js", $jsFiles[0]["baseName"]);
		Tester\Assert::same("js", $jsFiles[0]["folder"]);
		Tester\Assert::true(array_key_exists("hash", $jsFiles[0]));
		Tester\Assert::true(array_key_exists("file", $jsFiles[0]));


		$otherFiles = $presenter->webLoader->getOtherFiles();

		Tester\Assert::count(1, $otherFiles);
		Tester\Assert::count(7, $otherFiles[0]);
		Tester\Assert::true(array_key_exists("originalFile", $otherFiles[0]));
		Tester\Assert::same("other", $otherFiles[0]["tag"]);
		Tester\Assert::same("default", $otherFiles[0]["namespace"][0]);
		Tester\Assert::same("foo.txt", $otherFiles[0]["baseName"]);
		Tester\Assert::same("other", $otherFiles[0]["folder"]);
		Tester\Assert::true(array_key_exists("hash", $otherFiles[0]));
		Tester\Assert::true(array_key_exists("file", $otherFiles[0]));


		Tester\Assert::true($presenter->webLoader->getCssLoader() instanceof AlesWita\Components\WebLoader\Loader\Css);
		Tester\Assert::true($presenter->webLoader->getJsLoader() instanceof AlesWita\Components\WebLoader\Loader\Js);
	}

	/**
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setDebugMode(TRUE);
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/gettersTestTwo.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Getters");

		Tester\Assert::same(NULL, $presenter->webLoader->getExpiration());
		Tester\Assert::true($presenter->webLoader->getDebugMode());
		Tester\Assert::false($presenter->webLoader->getProductionMode());
		Tester\Assert::same(AlesWita\Components\WebLoader\Factory::CACHE_DEFAULT_NAMESPACE, $presenter->webLoader->getCacheNamespace());

		$cssFiles = $presenter->webLoader->getCssFiles();
		Tester\Assert::count(0, $cssFiles);

		$jsFiles = $presenter->webLoader->getJsFiles();
		Tester\Assert::count(0, $jsFiles);

		$otherFiles = $presenter->webLoader->getOtherFiles();
		Tester\Assert::count(0, $otherFiles);
	}
}


$test = new GettersTest;
$test->run();

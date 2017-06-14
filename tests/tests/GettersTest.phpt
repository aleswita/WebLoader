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


/**
 * @author Ales Wita
 * @license MIT
 */
final class GettersTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		if (is_dir(__DIR__ . "/css")) {
			Nette\Utils\FileSystem::delete(__DIR__ . "/css");
		}
		if (is_dir(__DIR__ . "/js")) {
			Nette\Utils\FileSystem::delete(__DIR__ . "/js");
		}
		if (is_dir(__DIR__ . "/other")) {
			Nette\Utils\FileSystem::delete(__DIR__ . "/other");
		}

		sleep(1);
		clearstatcache();
	}

	/**
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/gettersTestOne.neon");

		$container = $configurator->createContainer();


		// check service
		$service = $container->getService("webloader.webloader");

		Tester\Assert::same("1 WEEK", $service->getExpiration());
		Tester\Assert::same(__DIR__, $service->getWwwDir());
		Tester\Assert::false($service->getDebugMode());
		Tester\Assert::true($service->getProductionMode());
		Tester\Assert::same(13, strlen($service->getUniqueId()));
		Tester\Assert::true($service->getCache() instanceof Nette\Caching\Cache);
		Tester\Assert::same("foo", $service->getCacheNamespace());
		Tester\Assert::same("foo", $service->getCacheTag());
		Tester\Assert::true($service->getHttpRequest() instanceof Nette\Http\IRequest);


		// check css files
		$cssFiles = $service->getCssFiles();

		Tester\Assert::count(1, $cssFiles);
		Tester\Assert::count(7, $cssFiles[0]);
		Tester\Assert::true(array_key_exists("originalFile", $cssFiles[0]));
		Tester\Assert::same("css", $cssFiles[0]["tag"]);
		Tester\Assert::same("default", $cssFiles[0]["namespace"][0]);
		Tester\Assert::same("css.css", $cssFiles[0]["baseName"]);
		Tester\Assert::same("css", $cssFiles[0]["folder"]);
		Tester\Assert::true(array_key_exists("hash", $cssFiles[0]));
		Tester\Assert::true(array_key_exists("file", $cssFiles[0]));


		// check js files
		$jsFiles = $service->getJsFiles();

		Tester\Assert::count(1, $jsFiles);
		Tester\Assert::count(7, $jsFiles[0]);
		Tester\Assert::true(array_key_exists("originalFile", $jsFiles[0]));
		Tester\Assert::same("js", $jsFiles[0]["tag"]);
		Tester\Assert::same("default", $jsFiles[0]["namespace"][0]);
		Tester\Assert::same("js.js", $jsFiles[0]["baseName"]);
		Tester\Assert::same("js", $jsFiles[0]["folder"]);
		Tester\Assert::true(array_key_exists("hash", $jsFiles[0]));
		Tester\Assert::true(array_key_exists("file", $jsFiles[0]));


		// check other files
		$otherFiles = $service->getOtherFiles();

		Tester\Assert::count(1, $otherFiles);
		Tester\Assert::count(7, $otherFiles[0]);
		Tester\Assert::true(array_key_exists("originalFile", $otherFiles[0]));
		Tester\Assert::same("other", $otherFiles[0]["tag"]);
		Tester\Assert::same("default", $otherFiles[0]["namespace"][0]);
		Tester\Assert::same("foo.txt", $otherFiles[0]["baseName"]);
		Tester\Assert::same("other", $otherFiles[0]["folder"]);
		Tester\Assert::true(array_key_exists("hash", $otherFiles[0]));
		Tester\Assert::true(array_key_exists("file", $otherFiles[0]));


		Tester\Assert::true($service->getCssLoader() instanceof AlesWita\Components\WebLoader\Loader\Css);
		Tester\Assert::true($service->getJsLoader() instanceof AlesWita\Components\WebLoader\Loader\Js);
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


		// check service
		$service = $container->getService("webloader.webloader");

		Tester\Assert::same(NULL, $service->getExpiration());
		Tester\Assert::true($service->getDebugMode());
		Tester\Assert::false($service->getProductionMode());
		Tester\Assert::same(AlesWita\Components\WebLoader\Factory::CACHE_DEFAULT_NAMESPACE, $service->getCacheNamespace());
		Tester\Assert::same(AlesWita\Components\WebLoader\Factory::CACHE_DEFAULT_TAG, $service->getCacheTag());

		$cssFiles = $service->getCssFiles();
		Tester\Assert::count(0, $cssFiles);

		$jsFiles = $service->getJsFiles();
		Tester\Assert::count(0, $jsFiles);

		$otherFiles = $service->getOtherFiles();
		Tester\Assert::count(0, $otherFiles);
	}
}


$test = new GettersTest;
$test->run();

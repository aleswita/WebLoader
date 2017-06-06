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
require_once __DIR__ . "/../app/presenters/DebugModePresenter.php";
require_once __DIR__ . "/../app/router/Router.php";


/**
 * @author Ales Wita
 * @license MIT
 */
final class DebugModeTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setDebugMode(TRUE);
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/baseLinksTestOne.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("BaseLinks");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("BaseLinks", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		$source = (string) $response->getSource();


		$cssFiles = $presenter->webLoader->getCssFiles();

		Tester\Assert::count(1, $cssFiles);
		Nette\Utils\FileSystem::delete($cssFiles[0]["file"]);
		$presenter->webLoader->getCache()->clean([Nette\Caching\Cache::TAGS => [$presenter->webLoader->getCacheTag()]]);


		$presenter = $presenterFactory->createPresenter("BaseLinks");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("BaseLinks", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		$source = (string) $response->getSource();


		$cssFiles = $presenter->webLoader->getCssFiles();

		Tester\Assert::count(1, $cssFiles);
		Tester\Assert::true(file_exists($cssFiles[0]["file"]));
	}
}


$test = new DebugModeTest;
$test->run();

//Nette\Utils\FileSystem::delete(__DIR__ . "/css");
//Nette\Utils\FileSystem::delete(__DIR__ . "/js");
//Nette\Utils\FileSystem::delete(__DIR__ . "/other");

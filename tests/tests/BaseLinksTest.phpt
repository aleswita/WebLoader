<?php

/**
 * This file is part of the AlesWita\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 *
 * @phpVersion 7.1.0
 */

declare(strict_types=1);

namespace AlesWita\WebLoader\Tests\Tests;

use AlesWita;
use Nette;
use Tester;

require_once __DIR__ . "/../bootstrap.php";


/**
 * @author Ales Wita
 * @license MIT
 */
final class BaseLinksTest extends Tester\TestCase
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
		$configurator->addConfig(__DIR__ . "/../app/config/baseLinksTestOne.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("BaseLinks");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("BaseLinks", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$data = $dom->find("link");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("stylesheet", (string) $data[0]["rel"]);
		Tester\Assert::contains("http:/css/css.css?v=", (string) $data[0]["href"]);
		Tester\Assert::same("text/css", (string) $data[0]["type"]);


		$cssFiles = $presenter->webLoader->getCssFiles();
		$jsFiles = $presenter->webLoader->getJsFiles();

		Tester\Assert::count(1, $cssFiles);
		Tester\Assert::count(0, $jsFiles);
		Tester\Assert::true(file_exists($cssFiles[0]["file"]));
		Tester\Assert::same(md5_file($cssFiles[0]["file"]), md5_file($cssFiles[0]["originalFile"]));
	}

	/**
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/baseLinksTestTwo.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("BaseLinks");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("BaseLinks", "GET", ["action" => "two"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$data = $dom->find("script");

		Tester\Assert::count(1, $data);
		Tester\Assert::contains("http:/js/js.js?v=", (string) $data[0]["src"]);
		Tester\Assert::same("text/javascript", (string) $data[0]["type"]);


		$cssFiles = $presenter->webLoader->getCssFiles();
		$jsFiles = $presenter->webLoader->getJsFiles();

		Tester\Assert::count(0, $cssFiles);
		Tester\Assert::count(1, $jsFiles);
		Tester\Assert::true(file_exists($jsFiles[0]["file"]));
		Tester\Assert::same(md5_file($jsFiles[0]["file"]), md5_file($jsFiles[0]["originalFile"]));
	}
}


$test = new BaseLinksTest;
$test->run();

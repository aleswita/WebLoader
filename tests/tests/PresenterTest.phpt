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
require_once __DIR__ . "/../app/TestPresenter.php";
require_once __DIR__ . "/../app/Router.php";


final class PresenterTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/testOne.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Test");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Test", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$data = $dom->find("link");

		Tester\Assert::count(1, $data);
		Tester\Assert::same("stylesheet", (string) $data[0]["rel"]);
		Tester\Assert::contains("http:/css/css.css?v=", (string) $data[0]["href"]);
		Tester\Assert::same("text/css", (string) $data[0]["type"]);
	}

	/**
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/testTwo.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("Test");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("Test", "GET", ["action" => "two"]);
		$response = $presenter->run($request);

		$source = (string) $response->getSource();
		$dom = Tester\DomQuery::fromHtml($source);
		$data = $dom->find("script");

		Tester\Assert::count(1, $data);
		Tester\Assert::contains("http:/js/js.js?v=", (string) $data[0]["src"]);
		Tester\Assert::same("text/javascript", (string) $data[0]["type"]);
	}

	/**
	 * @return void
	 */
	public function ttestTwo(): void {
		Tester\Assert::true(FALSE);
	}
}


$test = new PresenterTest;
$test->run();

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
		$configurator->addConfig(__DIR__ . "/../app/config/debugModeTestOne.neon");

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType("Nette\\Application\\IPresenterFactory");

		$presenter = $presenterFactory->createPresenter("DebugMode");
		$presenter->autoCanonicalize = FALSE;
		$request = new Nette\Application\Request("DebugMode", "GET", ["action" => "one"]);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		$source = (string) $response->getSource();
		$cache = $presenter->webLoader->getCache();

		Tester\Assert::same($presenter->webLoader->getUniqueId(), $cache->load("uniqueId"));
	}
}


$test = new DebugModeTest;
$test->run();

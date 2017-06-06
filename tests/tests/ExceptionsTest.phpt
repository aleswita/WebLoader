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
final class ExceptionsTest extends Tester\TestCase
{
	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Missing parameter 'originalFile' in file configuration!
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestOne.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Missing parameter 'tag' in file configuration!
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestTwo.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Parameter 'namespace' must be array in file configuration!
	 * @return void
	 */
	public function testThree(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestThree.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException
	 * @return void
	 */
	public function testFour(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestFour.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Unknown file tag in configuration! Allowed tags: css, js, other
	 * @return void
	 */
	public function testFive(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestFive.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Missing parameter 'folder' in file configuration! For tag 'other' this tag is required.
	 * @return void
	 */
	public function testSix(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestSix.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Missing parameter 'originalFolder' in folder configuration!
	 * @return void
	 */
	public function testSeven(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestSeven.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Missing parameter 'tag' in folder configuration!
	 * @return void
	 */
	public function testEight(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestEight.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Parameter 'namespace' must be array in folder configuration!
	 * @return void
	 */
	public function testNine(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestNine.neon");

		$configurator->createContainer();
	}

	/**
	 * @throws AlesWita\Components\WebLoader\WebLoaderException Folder 'css' already have file with name 'css.css'!
	 * @return void
	 */
	public function testTen(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . "/../app/config/config.neon");
		$configurator->addConfig(__DIR__ . "/../app/config/exceptionsTestTen.neon");

		$configurator->createContainer();
	}
}


$test = new ExceptionsTest;
$test->run();

//Nette\Utils\FileSystem::delete(__DIR__ . "/css");
//Nette\Utils\FileSystem::delete(__DIR__ . "/js");
//Nette\Utils\FileSystem::delete(__DIR__ . "/other");

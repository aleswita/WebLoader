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

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author Ales Wita
 * @license MIT
 */
final class ExceptionsTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		if (is_dir(__DIR__ . '/css')) {
			Nette\Utils\FileSystem::delete(__DIR__ . '/css');
		}
		if (is_dir(__DIR__ . '/js')) {
			Nette\Utils\FileSystem::delete(__DIR__ . '/js');
		}
		if (is_dir(__DIR__ . '/other')) {
			Nette\Utils\FileSystem::delete(__DIR__ . '/other');
		}

		sleep(1);
		clearstatcache();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Missing parameter "originalFile" in file configuration!
	 * @return void
	 */
	public function testOne(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestOne.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Missing parameter "tag" in file configuration!
	 * @return void
	 */
	public function testTwo(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestTwo.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Parameter "namespace" must be array in file configuration!
	 * @return void
	 */
	public function testThree(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestThree.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException
	 * @return void
	 */
	public function testFour(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestFour.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Unknown file tag in configuration! Allowed tags: css, js, other
	 * @return void
	 */
	public function testFive(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestFive.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Missing parameter "folder" in file configuration! For tag "other" this tag is required.
	 * @return void
	 */
	public function testSix(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestSix.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Missing parameter "originalFolder" in folder configuration!
	 * @return void
	 */
	public function testSeven(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestSeven.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Missing parameter "tag" in folder configuration!
	 * @return void
	 */
	public function testEight(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestEight.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Parameter "namespace" must be array in folder configuration!
	 * @return void
	 */
	public function testNine(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestNine.neon');

		$configurator->createContainer();
	}


	/**
	 * @throws AlesWita\WebLoader\WebLoaderException Folder "css" already have file with name "css.css"!
	 * @return void
	 */
	public function testTen(): void {
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/exceptionsTestTen.neon');

		$configurator->createContainer();
	}
}


$test = new ExceptionsTest;
$test->run();

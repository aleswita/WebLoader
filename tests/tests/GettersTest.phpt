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
final class GettersTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function setUp(): void
	{
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
	 * @return void
	 */
	public function testOne(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/gettersTestOne.neon');

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType('Nette\\Application\\IPresenterFactory');

		$presenter = $presenterFactory->createPresenter('Getters');
		$presenter->autoCanonicalize = false;
		$request = new Nette\Application\Request('Getters', 'GET', ['action' => 'one']);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		Tester\Assert::same(__DIR__, $presenter->webLoader->wwwDir);
		Tester\Assert::false($presenter->webLoader->debugMode);
		Tester\Assert::true($presenter->webLoader->productionMode);
		Tester\Assert::same(13, strlen($presenter->webLoader->uniqueId));
		Tester\Assert::true($presenter->webLoader->cache instanceof Nette\Caching\Cache);
		Tester\Assert::same('foo', $presenter->webLoader->cacheNamespace);
		Tester\Assert::same('foo', $presenter->webLoader->cacheTag);
		Tester\Assert::true($presenter->webLoader->httpRequest instanceof Nette\Http\IRequest);
		Tester\Assert::same('1 WEEK', $presenter->webLoader->expiration);


		$cssFiles = $presenter->webLoader->getCssFiles();

		Tester\Assert::count(1, $cssFiles);
		Tester\Assert::count(7, $cssFiles[0]);
		Tester\Assert::true(array_key_exists('originalFile', $cssFiles[0]));
		Tester\Assert::same('css', $cssFiles[0]['tag']);
		Tester\Assert::same('default', $cssFiles[0]['namespace'][0]);
		Tester\Assert::same('css.css', $cssFiles[0]['baseName']);
		Tester\Assert::same('css', $cssFiles[0]['folder']);
		Tester\Assert::true(array_key_exists('hash', $cssFiles[0]));
		Tester\Assert::true(array_key_exists('file', $cssFiles[0]));


		$jsFiles = $presenter->webLoader->jsFiles;

		Tester\Assert::count(1, $jsFiles);
		Tester\Assert::count(7, $jsFiles[0]);
		Tester\Assert::true(array_key_exists('originalFile', $jsFiles[0]));
		Tester\Assert::same('js', $jsFiles[0]['tag']);
		Tester\Assert::same('default', $jsFiles[0]['namespace'][0]);
		Tester\Assert::same('js.js', $jsFiles[0]['baseName']);
		Tester\Assert::same('js', $jsFiles[0]['folder']);
		Tester\Assert::true(array_key_exists('hash', $jsFiles[0]));
		Tester\Assert::true(array_key_exists('file', $jsFiles[0]));


		$otherFiles = $presenter->webLoader->otherFiles;

		Tester\Assert::count(1, $otherFiles);
		Tester\Assert::count(7, $otherFiles[0]);
		Tester\Assert::true(array_key_exists('originalFile', $otherFiles[0]));
		Tester\Assert::same('other', $otherFiles[0]['tag']);
		Tester\Assert::same('default', $otherFiles[0]['namespace'][0]);
		Tester\Assert::same('foo.txt', $otherFiles[0]['baseName']);
		Tester\Assert::same('other', $otherFiles[0]['folder']);
		Tester\Assert::true(array_key_exists('hash', $otherFiles[0]));
		Tester\Assert::true(array_key_exists('file', $otherFiles[0]));


		$htmlTags = $presenter->webLoader->htmlTags;

		Tester\Assert::count(1, $htmlTags);
		Tester\Assert::count(2, $htmlTags[0]);
		Tester\Assert::true($htmlTags[0]['tag'] instanceof Nette\Utils\Html);
		Tester\Assert::same('default', $otherFiles[0]['namespace'][0]);


		Tester\Assert::true($presenter->webLoader->getCssLoader() instanceof AlesWita\WebLoader\Loader\Css);
		Tester\Assert::true($presenter->webLoader->getJsLoader() instanceof AlesWita\WebLoader\Loader\Js);
		Tester\Assert::true($presenter->webLoader->getTagLoader() instanceof AlesWita\WebLoader\Loader\Tag);
	}


	/**
	 * @return void
	 */
	public function testTwo(): void
	{
		$configurator = new Nette\Configurator();
		$configurator->setDebugMode(true);
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/../app/config/gettersTestTwo.neon');

		$container = $configurator->createContainer();
		$presenterFactory = $container->getByType('Nette\\Application\\IPresenterFactory');

		$presenter = $presenterFactory->createPresenter('Getters');
		$presenter->autoCanonicalize = false;
		$request = new Nette\Application\Request('Getters', 'GET', ['action' => 'one']);
		$response = $presenter->run($request);

		Tester\Assert::true($response instanceof Nette\Application\Responses\TextResponse);
		Tester\Assert::true($response->getSource() instanceof Nette\Application\UI\ITemplate);

		Tester\Assert::true($presenter->webLoader->debugMode);
		Tester\Assert::false($presenter->webLoader->productionMode);
		Tester\Assert::same(AlesWita\WebLoader\Factory::CACHE_DEFAULT_NAMESPACE, $presenter->webLoader->cacheNamespace);
		Tester\Assert::same(AlesWita\WebLoader\Factory::CACHE_DEFAULT_TAG, $presenter->webLoader->cacheTag);
		Tester\Assert::same(null, $presenter->webLoader->expiration);

		$cssFiles = $presenter->webLoader->cssFiles;
		Tester\Assert::count(0, $cssFiles);

		$jsFiles = $presenter->webLoader->jsFiles;
		Tester\Assert::count(0, $jsFiles);

		$otherFiles = $presenter->webLoader->otherFiles;
		Tester\Assert::count(0, $otherFiles);

		$htmlTags = $presenter->webLoader->htmlTags;
		Tester\Assert::count(0, $htmlTags);
	}
}


$test = new GettersTest;
$test->run();

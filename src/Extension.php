<?php

/**
 * This file is part of the AlesWita\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\WebLoader;

use Nette;


/**
 * @author Ales Wita
 * @license MIT
 */
class Extension extends Nette\DI\CompilerExtension
{
	/** @var array */
	public $defaults = [
		'expiration' => null,
		'cache' => [
			'namespace' => null,
			'tag' => null,
		],
		'files' => null,
		'folders' => null,
		'htmlTags' => null,
	];


	/**
	 * @return void
	 */
	public function loadConfiguration(): void
	{
		$config = $this->getConfig($this->defaults);
		$container = $this->getContainerBuilder();

		$webLoader = $container->addDefinition($this->prefix('webloader'))
			->setClass('AlesWita\\WebLoader\\Factory')
			->addSetup('$service->setWwwDir(?)', [$container->parameters['wwwDir']])
			->addSetup('$service->setDebugMode(?)', [$container->parameters['debugMode']])
			->addSetup('$service->setProductionMode(?)', [$container->parameters['productionMode']])
			->addSetup('$service->setUniqueId(?)', [uniqid()])
			->addSetup('$service->setCacheStorage(?)', [$container->getDefinitionByType('Nette\\Caching\\IStorage')])
			->addSetup('$service->setHttpRequest(?)', [$container->getDefinitionByType('Nette\\Http\\IRequest')]);

		if ($config['expiration'] !== null) {
			$webLoader->addSetup('$service->setExpiration(?)', [$config['expiration']]);
		}
		if ($config['cache']['namespace'] !== null) {
			$webLoader->addSetup('$service->setCacheNamespace(?)', [$config['cache']['namespace']]);
		}
		if ($config['cache']['tag'] !== null) {
			$webLoader->addSetup('$service->setCacheTag(?)', [$config['cache']['tag']]);
		}

		if (is_array($config['folders'])) {
			foreach ($config['folders'] as $folderSettings) {
				if (!isset($folderSettings['originalFolder'])) {
					throw new WebLoaderException('Missing parameter "originalFolder" in folder configuration!');
				}
				if (!isset($folderSettings['tag'])) {
					throw new WebLoaderException('Missing parameter "tag" in folder configuration!');
				}
				if (!isset($folderSettings['namespace'])) {
					$folderSettings['namespace'] = (array) Factory::DEFAULT_NAMESPACE;
				}
				if (!is_array($folderSettings['namespace'])) {
					throw new WebLoaderException('Parameter "namespace" must be array in folder configuration!');
				}
				if (!isset($folderSettings['masks'])) {
					$folderSettings['masks'] = '*';
				}
				if (!isset($folderSettings['limithDepth'])) {
					$folderSettings['limithDepth'] = 0;
				}

				$finder = Nette\Utils\Finder::findFiles($folderSettings['masks'])
					->from($folderSettings['originalFolder'])
					->limitDepth($folderSettings['limithDepth']);

				foreach ($finder as $file) {
					$config['files'][] = [
						//'originalFile' => $file->getLinkTarget(),// failed on Linux
						'originalFile' => $file->getRealPath(),
						'tag' => $folderSettings['tag'],
						'namespace' => $folderSettings['namespace'],
						//'baseName' => basename($file->getLinkTarget()),// failed on Linux
						'baseName' => basename($file->getRealPath()),
						'folder' => $folderSettings['folder'] ?? null,
					];
				}
			}
		}

		if (is_array($config['files'])) {
			$files = [];
			$allowedTags = [Factory::TAG_FILE_CSS, Factory::TAG_FILE_JS, Factory::TAG_FILE_OTHER];

			foreach ($config['files'] as $fileSettings) {
				if (!isset($fileSettings['originalFile'])) {
					throw new WebLoaderException('Missing parameter "originalFile" in file configuration!');
				}
				if (!is_file($fileSettings['originalFile'])) {
					throw new WebLoaderException('Can not find "' . $fileSettings['originalFile'] . '"!');
				}
				if (!isset($fileSettings['tag'])) {
					throw new WebLoaderException('Missing parameter "tag" in file configuration!');
				}
				if (!in_array($fileSettings['tag'], $allowedTags, true)) {
					throw new WebLoaderException('Unknown file tag in configuration! Allowed tags: ' . implode(', ', $allowedTags));
				}
				if (!isset($fileSettings['namespace'])) {
					$fileSettings['namespace'] = (array) Factory::DEFAULT_NAMESPACE;
				}
				if (!is_array($fileSettings['namespace'])) {
					throw new WebLoaderException('Parameter "namespace" must be array in file configuration!');
				}
				if (!isset($fileSettings['baseName'])) {
					$fileSettings['baseName'] = basename($fileSettings['originalFile']);
				}
				if (isset($fileSettings['folder'])) {
					$fileSettings['folder'] = Nette\Utils\Strings::trim($fileSettings['folder'], '\\/');
				}

				$fileSettings['hash'] = md5_file($fileSettings['originalFile']);

				switch ($fileSettings['tag']) {
					case Factory::TAG_FILE_CSS:
						$fileSettings['folder'] = $fileSettings['folder'] ?? Factory::DEFAULT_FOLDER_CSS;
						$fileSettings['file'] = $container->parameters['wwwDir'] . '/' . $fileSettings['folder'] . '/' . $fileSettings['baseName'];
						$webLoader->addSetup('$service->addCssFile(?)', [$fileSettings]);
						break;

					case Factory::TAG_FILE_JS:
						$fileSettings['folder'] = $fileSettings['folder'] ?? Factory::DEFAULT_FOLDER_JS;
						$fileSettings['file'] = $container->parameters['wwwDir'] . '/' . $fileSettings['folder'] . '/' . $fileSettings['baseName'];
						$webLoader->addSetup('$service->addJsFile(?)', [$fileSettings]);
						break;

					case Factory::TAG_FILE_OTHER:
						if (!isset($fileSettings['folder'])) {
							throw new WebLoaderException('Missing parameter "folder" in file configuration! For tag "other" this tag is required.');
						}

						$fileSettings['file'] = $container->parameters['wwwDir'] . '/' . $fileSettings['folder'] . '/' . $fileSettings['baseName'];
						$webLoader->addSetup('$service->addOtherFile(?)', [$fileSettings]);
						break;
				}

				if (!isset($files[$fileSettings['folder']])) {
					$files[$fileSettings['folder']][] = $fileSettings['baseName'];
				} else {
					if (in_array($fileSettings['baseName'], $files[$fileSettings['folder']], true)) {
						throw new WebLoaderException('Folder "' . $fileSettings['folder'] . '" already have file with name "' . $fileSettings['baseName'] . '"!');
					} else {
						$files[$fileSettings['folder']][] = $fileSettings['baseName'];
					}
				}
			}
		}

		if (is_array($config['htmlTags'])) {
			foreach ($config['htmlTags'] as $htmlTagSettings) {
				if (!isset($htmlTagSettings['tag'])) {
					throw new WebLoaderException('Missing parameter "tag" in HTML tag configuration!');
				}
				if (!isset($htmlTagSettings['namespace'])) {
					$htmlTagSettings['namespace'] = (array) Factory::DEFAULT_NAMESPACE;
				}
				if (!is_array($htmlTagSettings['namespace'])) {
					throw new WebLoaderException('Parameter "namespace" must be array in HTML tags configuration!');
				}

				$webLoader->addSetup('$service->addHtmlTag(?)', [$htmlTagSettings]);
			}
		}
	}
}

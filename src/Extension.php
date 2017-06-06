<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader;

use Nette;
use Nette\Utils;


/**
 * @author Aleš Wita
 */
class Extension extends Nette\DI\CompilerExtension
{
	/** @var array */
	public $defaults = [
		"expiration" => NULL,
		"cache" => [
			"namespace" => NULL,
			"tag" => NULL,
		],
		"files" => NULL,
		"folders" => NULL,
	];

	/**
	 * @return void
	 */
	public function loadConfiguration(): void {
		$config = $this->getConfig($this->defaults);
		$container = $this->getContainerBuilder();

		$webLoader = $container->addDefinition($this->prefix("webloader"))
			->setClass("AlesWita\\Components\\WebLoader\\Factory")
			->addSetup("\$service->setWwwDir(?)", [$container->parameters["wwwDir"]])
			->addSetup("\$service->setDebugMode(?)", [$container->parameters["debugMode"]])
			->addSetup("\$service->setProductionMode(?)", [$container->parameters["productionMode"]])
			->addSetup("\$service->setUniqueId(?)", [uniqid()])
			->addSetup("\$service->setCacheStorage(?)", [$container->getDefinitionByType("Nette\\Caching\\IStorage")])
			->addSetup("\$service->setHttpRequest(?)", [$container->getDefinitionByType("Nette\\Http\\IRequest")]);

		if ($config["expiration"] !== NULL) {
			$webLoader->addSetup("\$service->setExpiration(?)", [$config["expiration"]]);
		}
		if ($config["cache"]["namespace"] !== NULL) {
			$webLoader->addSetup("\$service->setCacheNamespace(?)", [$config["cache"]["namespace"]]);
		}
		if ($config["cache"]["tag"] !== NULL) {
			$webLoader->addSetup("\$service->setCacheTag(?)", [$config["cache"]["tag"]]);
		}

		if (is_array($config["folders"])) {
			foreach ($config["folders"] as $folderSettings) {
				if (!isset($folderSettings["originalFolder"])) {
					throw new WebLoaderException("Missing parameter 'originalFolder' in folder configuration!");
				}
				if (!isset($folderSettings["tag"])) {
					throw new WebLoaderException("Missing parameter 'tag' in folder configuration!");
				}
				if (!isset($folderSettings["namespace"])) {
					$folderSettings["namespace"] = (array) Factory::DEFAULT_NAMESPACE;
				}
				if (!is_array($folderSettings["namespace"])) {
					throw new WebLoaderException("Parameter 'namespace' must be array in folder configuration!");
				}
				if (!isset($folderSettings["masks"])) {
					$folderSettings["masks"] = "*";
				}
				if (!isset($folderSettings["limithDepth"])) {
					$folderSettings["limithDepth"] = 0;
				}

				$finder = Utils\Finder::findFiles($folderSettings["masks"])
					->from($folderSettings["originalFolder"])
					->limitDepth($folderSettings["limithDepth"]);

				foreach ($finder as $file) {
					$config["files"][] = [
						//"originalFile" => $file->getLinkTarget(),// failed on Linux
						"originalFile" => $file->getRealPath(),
						"tag" => $folderSettings["tag"],
						"namespace" => $folderSettings["namespace"],
						//"baseName" => basename($file->getLinkTarget()),// failed on Linux
						"baseName" => basename($file->getRealPath()),
						"folder" => (isset($folderSettings["folder"]) ? $folderSettings["folder"] : NULL),
					];
				}
			}
		}

		if (is_array($config["files"])) {
			$files = [];
			$allowedTags = [Factory::FILE_TAG_CSS, Factory::FILE_TAG_JS, Factory::FILE_TAG_OTHER];

			foreach ($config["files"] as $fileSettings) {
				if (!isset($fileSettings["originalFile"])) {
					throw new WebLoaderException("Missing parameter 'originalFile' in file configuration!");
				}
				if (!is_file($fileSettings["originalFile"])) {
					throw new WebLoaderException("Can not find '{$fileSettings["originalFile"]}'!");
				}
				if (!isset($fileSettings["tag"])) {
					throw new WebLoaderException("Missing parameter 'tag' in file configuration!");
				}
				if (!in_array($fileSettings["tag"], $allowedTags, TRUE)) {
					throw new WebLoaderException("Unknown file tag in configuration! Allowed tags: " . implode(", ", $allowedTags));
				}
				if (!isset($fileSettings["namespace"])) {
					$fileSettings["namespace"] = (array) Factory::DEFAULT_NAMESPACE;
				}
				if (!is_array($fileSettings["namespace"])) {
					throw new WebLoaderException("Parameter 'namespace' must be array in file configuration!");
				}
				if (!isset($fileSettings["baseName"])) {
					$fileSettings["baseName"] = basename($fileSettings["originalFile"]);
				}
				if (isset($fileSettings["folder"])) {
					$fileSettings["folder"] = Utils\Strings::trim($fileSettings["folder"], "\\/");
				}

				$fileSettings["hash"] = md5_file($fileSettings["originalFile"]);

				switch ($fileSettings["tag"]) {
					case Factory::FILE_TAG_CSS:
						$fileSettings["folder"] = (isset($fileSettings["folder"]) ? $fileSettings["folder"] : Factory::DEFAULT_FOLDER_CSS);
						$fileSettings["file"] = "{$container->parameters["wwwDir"]}/{$fileSettings["folder"]}/{$fileSettings["baseName"]}";
						$webLoader->addSetup("\$service->addCssFile(?)", [$fileSettings]);
						break;

					case Factory::FILE_TAG_JS:
						$fileSettings["folder"] = (isset($fileSettings["folder"]) ? $fileSettings["folder"] : Factory::DEFAULT_FOLDER_JS);
						$fileSettings["file"] = "{$container->parameters["wwwDir"]}/{$fileSettings["folder"]}/{$fileSettings["baseName"]}";
						$webLoader->addSetup("\$service->addJsFile(?)", [$fileSettings]);
						break;

					case Factory::FILE_TAG_OTHER:
						if (!isset($fileSettings["folder"])) {
							throw new WebLoaderException("Missing parameter 'folder' in file configuration! For tag 'other' this tag is required.");
						}

						$fileSettings["file"] = "{$container->parameters["wwwDir"]}/{$fileSettings["folder"]}/{$fileSettings["baseName"]}";
						$webLoader->addSetup("\$service->addOtherFile(?)", [$fileSettings]);
						break;
				}

				if (!isset($files[$fileSettings["folder"]])) {
					$files[$fileSettings["folder"]][] = $fileSettings["baseName"];
				} else {
					if (in_array($fileSettings["baseName"], $files[$fileSettings["folder"]], TRUE)) {
						throw new WebLoaderException("Folder '{$fileSettings["folder"]}' already have file with name '{$fileSettings["baseName"]}'!");
					} else {
						$files[$fileSettings["folder"]][] = $fileSettings["baseName"];
					}
				}
			}
		}
	}
}

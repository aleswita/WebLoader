<?php

/**
 * This file is part of the AlesWita\Components\WebLoader
 * Copyright (c) 2017 Ales Wita (aleswita+github@gmail.com)
 */

declare(strict_types=1);

namespace AlesWita\Components\WebLoader\Tests\App\Router;

use Nette;


/**
 * @author Ale� Wita
 * @license MIT
 */
final class Router
{
	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter(): Nette\Application\IRouter {
		$route = new Nette\Application\Routers\RouteList;
		$route[] = new Nette\Application\Routers\Route("<presenter>/<action>[/<id>]", "Home:default");
		return $route;
	}
}

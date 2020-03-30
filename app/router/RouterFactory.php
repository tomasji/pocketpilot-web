<?php

declare(strict_types=1);

namespace PP;

use Nette;
use Nette\Application\Routers\RouteList;
use Ublaboo\ApiRouter\ApiRoute;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList();
        $router->add(new ApiRoute('/api/v1/login', 'APIv1:Login'));
        $router->add(new ApiRoute('/api/v1/tracks', 'APIv1:Tracks'));
        $router->addRoute('[<lang=cs cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');
        return $router;
    }
}

<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;

class UsersHandlerFactory
{
    public function __invoke(ContainerInterface $container): UsersHandler
    {
        return new UsersHandler($container);
    }
}

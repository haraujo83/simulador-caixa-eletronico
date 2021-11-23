<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

/**
 *
 */
class UsersHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return UsersHandler
     */
    public function __invoke(ContainerInterface $container): UsersHandler
    {
        return new UsersHandler($container);
    }
}

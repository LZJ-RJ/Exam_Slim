<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Domain\Url\UrlRepository;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Url\InMemoryUrlRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
    ]);

    $containerBuilder->addDefinitions([
        UrlRepository::class => \DI\autowire(InMemoryUrlRepository::class),
    ]);

};

<?php

namespace PromptBuilder\core;

use DI\Container;
use DI\ContainerBuilder;
use function DI\autowire;

use PromptBuilder\Repositories\UserRepository;
//use PromptBuilder\Repositories\AnotherRepository;

class AppContainer {
    public static function getContainer(): Container {
        $builder = new ContainerBuilder();

        // Автоматическая регистрация всех репозиториев, но создание - только при необходимости
        $builder->addDefinitions([
            UserRepository::class => autowire(),
            //AnotherRepository::class => autowire(),
            // и так далее...
        ]);

        return $builder->build();
    }
}

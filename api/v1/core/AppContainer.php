<?php

namespace Landing\core;

use DI\Container;
use DI\ContainerBuilder;
use function DI\autowire;

//use Landing\Repositories\UserRepository;
//use Landing\Repositories\AnotherRepository;

class AppContainer {
    public static function getContainer(): Container {
        $builder = new ContainerBuilder();

        // Automatically register all repositories, but create only when needed
        $builder->addDefinitions([
            //UserRepository::class => autowire(),
            //AnotherRepository::class => autowire(),
            // and so on..
        ]);

        return $builder->build();
    }
}

# Consoleful

A simple library to work with Symfony Console Component and Symfony Dependency Injection Component
=======
[![Latest Stable Version](https://poser.pugx.org/luismulinari/consoleful/v/stable.svg)](https://packagist.org/packages/luismulinari/consoleful)
[![Code Climate](https://codeclimate.com/github/luismulinari/consoleful/badges/gpa.svg)](https://codeclimate.com/github/luismulinari/consoleful)
[![Test Coverage](https://codeclimate.com/github/luismulinari/consoleful/badges/coverage.svg)](https://codeclimate.com/github/luismulinari/consoleful)
[![Build Status](https://travis-ci.org/luismulinari/consoleful.svg?branch=master)](https://travis-ci.org/luismulinari/consoleful)

## Instalation

Use composer to add consoleful to your app

```"luismulinari/consoleful": "*"```

## Usage (example)
application.php - Entry Point
```php
<?php
use Lcobucci\DependencyInjection\ContainerConfig;
use LuisMulinari\Consoleful\Application;

$autoloader = require __DIR__ . '/vendor/autoload.php';

$application = new Application(
    'Application name',
    'Version',
    new ContainerConfig('/home/example/services.xml') // services.[xml|yml|php]
);

$application->add(new ExampleCommand());

$application->run();
```

ExampleCommand.php - Command file
```php
<?php

namespace Vendor\ExampleApp\Command;

use Lcobucci\DependencyInjection\ContainerInjector;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ExampleCommand extends Command implements ContainerAwareInterface
{
    use ContainerInjector;
    
    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        
        $container->get('service.example');
        $container->getParameter('parameter.example');
    }
}
```

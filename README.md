# Consoleful

A simple library to work with Symfony Console Component and Symfony Dependency Injection Component

=======
[![Latest Stable Version](https://poser.pugx.org/luismulinari/consoleful/v/stable.svg)](https://packagist.org/packages/luismulinari/consoleful)
[![Build Status](https://travis-ci.org/luismulinari/consoleful.svg?branch=master)](https://travis-ci.org/luismulinari/consoleful)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/luismulinari/consoleful/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/luismulinari/consoleful/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/luismulinari/consoleful/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/luismulinari/consoleful/?branch=master)

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
    new ContainerConfig(__DIR__ . 'services.xml') // services.[xml|yml|php]
);

$application->add(new ExampleCommand());

$application->run();
```

ExampleCommand.php - Command file
```php
<?php

namespace Vendor\ExampleApp\Command;

use LuisMulinari\Consoleful\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ExampleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName("example");
        $this->setDescription('Description example');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        
        $container->get('service.example');
        $container->getParameter('parameter.example');
    }
}
```
You can see other examples [here](https://github.com/luismulinari/consoleful/tree/master/example)

<?php

namespace App;

use DI\ContainerBuilder;
use DI\Bridge\Slim\App as DIBridge;

class App extends DIBridge
{
	protected function configureContainer(ContainerBuilder $builder)
	{
		$builder->addDefinitions(CONFIG_PATH.'/app.php');
		$builder->addDefinitions(CONFIG_PATH.'/store.php');
		$builder->addDefinitions(CONFIG_PATH.'/provision.php');
		$builder->addDefinitions(APP_PATH.'/container.php');
	}
}
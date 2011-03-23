<?php

class routingGeneratecacheTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('server', sfCommandArgument::REQUIRED, 'The web server'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('debug', null, sfCommandOption::PARAMETER_REQUIRED, 'debug boolean', false),
      // add your own options here
    ));

    $this->namespace        = 'routing';
    $this->name             = 'generate-cache';
    $this->briefDescription = 'generate cached frontend controller according to routing config';
    $this->detailedDescription = <<<EOF
The [routing:generate-cache|INFO] task generates cached frontend controllers according to routing config so that there is no routing done in php no more.
Call it with:

  [php symfony routing:generate-cache|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here
    $debug = !($options['debug']===false);    
    
    $web_server_config = new WebServerConfiguration(sfConfig::get('app_asCachedRoutingPlugin_web_server_config'), $debug);
    $web_server_config->initFor($arguments['server']);
    
    $controller_config = new ControllerConfiguration(
                                sfConfig::get('app_asCachedRoutingPlugin_controller'),
                                $options['application'],
                                $options['env']
                              );
    $controller_config->init();
    
    $app = new asCachedRoutingApplication($this->configuration, $web_server_config, $controller_config);
    $app->doProcess();
  }
}









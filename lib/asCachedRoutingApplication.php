<?php 

class asCachedRoutingApplication
{
  protected $configuration, $web_server_config, $controller_config;
  
  public function __construct($configuration, $web_server_config, $controller_config)
  {
    $this->configuration      = $configuration;
    $this->web_server_config  = $web_server_config;
    $this->controller_config  = $controller_config;
  }
  
  public function doProcess()  
  {    
    $formatted_routes = $this->retrieveFormattedRoutes();
    $this->createControllerFile($formatted_routes);
  }
  
  protected function retrieveFormattedRoutes()
  {
    $config =  $this->configuration->getConfigCache()->checkConfig('config/routing.yml', true);
    $this->log('going to include the file: '.$config);
    return $this->formatRoutes(include($config));
  }
  
  protected function formatRoutes($routes)
  {
    $output = array();
    
    foreach($routes as $name=>$route)
    {
      $formatted_route = $this->formatRoute($route);
      
      if($formatted_route === null)
      { 
        continue; 
      }
      else 
      {
        $output[$name] = $formatted_route;
      }
    }

    return $output;
  }
  
  protected function formatRoute(sfRoute $route)
  {
      //rules with wildcards as module/action they should be at the end of the htaccess file
      $array_defaults = $route->getDefaults();
      if( ! isset($array_defaults['module']) || ! isset($array_defaults['action'])  ) 
      {
        return null; 
      }
      
      return $this->web_server_config->getRuleInstance($route);
  }
  
  protected function createControllerFile($formatted_routes)
  {
    $file = $this->web_server_config->getConfigFileInstance();
    $file->loadRulesBlock($this->processBlock($formatted_routes));
    $file->create();
  }
  
  private function processBlock($arrayRules)
  {
    $out = '';
    
    foreach($arrayRules as $name=>$rule)
    {
      $target_file = $this->controller_config->getTargetFileName($name);
      
      $controller = $this->controller_config->getCachedControllerInstance($name, $rule);
      $controller->create();
      
      $out .= $rule->getRule($target_file)."\n";
    }
    return $out;
  }
  
  protected function log($msg)
  {
    echo '{asCachedRoutingApplication} '.$msg."\n";
  }
}
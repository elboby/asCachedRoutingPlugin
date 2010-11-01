<?php 

class asCachedRoutingApplication
{
  protected $configuration;
  
  public function __construct($configuration)
  {
    $this->configuration = $configuration;
  }
  
  public function doProcess()  
  {    
    //get info from routing config
    $config =  $this->configuration->getConfigCache()->checkConfig('config/routing.yml', true);
    $this->log('going to include the file: '.$config);
    $formatted_routes = $this->parseConfig(include($config));
    
    $file = WebServerHandlerFactory::getConfigFileInstance(sfConfig::get('app_asCachedRoutingPlugin_web_server_config'));
    $file->loadRulesBlock($this->convertAsBlock($formatted_routes));
    $file->create();
  }
  
  protected function parseConfig($routes)
  {
    $output = array();
    
    foreach($routes as $name=>$route)
    {
      $array_defaults = $route->getDefaults();
      
      //rules with wildcards as module/action they should be at the end of the htaccess file
      if( ! isset($array_defaults['module']) || !isset($array_defaults['action'])  )
      {
        continue;
      }
      
      $output[$name] = WebServerHandlerFactory::getRuleInstance(
                                                  sfConfig::get('app_asCachedRoutingPlugin_web_server_config'),
                                                  $route
                                                );
    }

    return $output;
  }
  
  private function convertAsBlock($arrayRules)
  {
    $controller_config = sfConfig::get('app_asCachedRoutingPlugin_controller');
    
    
    $out = '';
    foreach($arrayRules as $name=>$rule)
    {
      $target_file = $controller_config['target_directory'].$name.'.php';
      
      $out .= $rule->getRule($target_file)."\n";
      
      $controller = new CachedControllerFile(
            'frontend',
            'prod',
            sfConfig::get('sf_web_dir').'/'.$target_file
          );
      $controller->loadTemplate($controller_config['template_path']);
      $controller->loadRule($name, $rule->getComposite());
      
      if($rule->hasPatternKey())
      {
        $controller->loadPatternKeys($rule->getPatternKeyAsArray());
      }
      
      $controller->create();
    }
    return $out;
  }
  
  protected function log($msg)
  {
    echo '{asCachedRoutingApplication} '.$msg."\n";
  }
}
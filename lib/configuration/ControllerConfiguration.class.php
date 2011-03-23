<?php

class ControllerConfiguration
{
  protected $config, $application, $environment;
  
  public function __construct($config, $application, $environment)
  {
    $this->config = $config;
    $this->application = $application;
    $this->environment = $environment;
  }
  
  public function init()
  {}
  
  public function getTargetFileName($name)
  {
    return $this->config['target_directory'].'/'.$name.'.php';
  }
  
  public function getCachedControllerInstance($name, $rule)
  {
    $controller = new CachedControllerFile(
                  $this->application, $this->environment,
                  sfConfig::get('sf_web_dir').'/'.$this->getTargetFileName($name)
                );
    $controller->loadTemplate($this->config['template_path']);
    $controller->loadRule($name, $rule->getComposite());
            
    if($rule->hasPatternKey())
    {
      $controller->loadPatternKeys($rule->getPatternKeyAsArray());
    }
            
    return $controller;
  }  
}
<?php

class ControllerConfiguration
{
  protected $config;
  
  public function __construct($config)
  {
    $this->config = $config;
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
                  'frontend', 'prod',
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
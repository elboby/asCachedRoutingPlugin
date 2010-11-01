<?php

class WebServerConfiguration
{
  protected $config, $server;
  
  public function __construct($config)
  {
    $this->config = $config;
  }
  
  public function initFor($server)
  {
    $this->server = $server;
  }
  
  public function getRuleInstance(sfRoute $route)
  {
    $class_config = $this->config[ $this->server ][ 'rule_format' ];
    
    $param = (isset($class_config['class']['parameters']))?$class_config['class']['parameters']: array();
    $rule = new $class_config['class']($route, $param);
    return $rule;
  }
  
  public function getConfigFileInstance()
  {
    $class_config = $this->config[ $this->server ][ 'config_file' ];
    
    $file = new $class_config['class']($class_config['target_path']);
    $file->loadTemplate($class_config['template_path']);
    return $file;
  }
}
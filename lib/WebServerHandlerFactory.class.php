<?php

class WebServerHandlerFactory
{
  static $config_array;
  
  static function loadConfiguration($config_array)
  {
    self::$config_array = $config_array;
  }
  
  static function getConfigurationFor($web_server)
  {
    return self::$config_array[$web_server];
  }
  
  static function getConfigFileInstance($config_array)
  {
    $class_config = self::getConfigurationFor($config_array['type']);
    
    $file = new $class_config['config_file']['class']($config_array['target_path']);
    $file->loadTemplate($config_array['template_path']);
    return $file;
  }
    
  static function getRuleInstance($config_array, sfRoute $route)
  {
    $class_config = self::getConfigurationFor($config_array['type']);
    
    $rule = new $class_config['rule_format']['class']($route, $class_config['rule_format']['parameters']);
    return $rule;
  }
}
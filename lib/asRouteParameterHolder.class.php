<?php

class asRouteParameterHolder
{
  static $instance;
  
  public static function getInstance()
  {
    if(isset(self::$instance))
    {
      return self::$instance;
    }
    
    self::$instance = new asRouteParameterHolder;
    return self::$instance;
  }
  
  protected $routeName, $parameters;
  
  public function setRouteName($name)
  {
    $this->routeName = $name;
  }
  public function getRouteName()
  {
    return $this->routeName;
  }
  
  public function setParameters($array)
  {
    $this->parameters = $array;
  }
  public function getParameters()
  {
    return $this->parameters;
  }
}
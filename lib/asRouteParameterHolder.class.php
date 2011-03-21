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
  
  public function setStarParameters($star_string)
  {
    $array = explode('/', $star_string);
    
    $array_param = array();
    foreach($array as $value)
    {
      if($value == '')
      {
        return;
      }
      
      if(isset($holder))
      {
        $array_param[$holder] = $value;
        unset($holder); 
      }
      else
      {
        $holder = $value;
      }
    }
    if(isset($holder))
    {
      $array_param[$holder] = true;
      unset($holder);
    }
    
    $this->parameters = array_merge($array_param, $this->parameters);
  }
}
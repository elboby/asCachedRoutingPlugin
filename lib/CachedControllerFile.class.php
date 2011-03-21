<?php

class CachedControllerFile extends FromTemplateFile
{
  public function __construct($application, $environment, $path)
  {
    $this->addReplacement('###%APPLICATION%###', $application);
    $this->addReplacement('###%ENVIRONMENT%###', $environment);
    
    $this->addReplacement('###%PARAMETERS_ARRAY%###', '');
    
    parent::__construct($path);
  }
  
  public function loadRule($name, sfRoute $route)
  {
    $defaults = $route->getDefaults();
    
    $this->addReplacement('###%ROUTENAME%###', $name);
    $this->addReplacement('###%MODULE%###', $defaults['module']);
    $this->addReplacement('###%ACTION%###', $defaults['action']);
    
    unset($defaults['module']);
    unset($defaults['action']);
    if(count($defaults) > 0)
    {
      $txt = '';
      foreach($defaults as $key=>$value)
      {
        $txt .= "'".$key."' => '".$value."',\n";
      }
  
      $this->addReplacement('###%PARAMETERS_DEFAULT_ARRAY%###', $txt);
    }
  }
  
  public function loadPatternKeys($array)
  {
    $txt = '';
    foreach($array as $key)
    {
      $txt .= "'".$key."' => \$_GET['".$key."'],\n";
    }
    
    $this->addReplacement('###%PARAMETERS_ARRAY%###', $txt);
  }
}
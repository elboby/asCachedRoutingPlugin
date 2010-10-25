<?php

abstract class RuleFormatter
{
  private $route;
  private $regex;
  private $formatted;
  protected $has_pattern_key;
  protected $pattern_keys;
  protected $options;
  
  public function __construct(sfRoute $route, $options)
  {
    $this->route = $route;
    $this->formatted = false;
    $this->has_pattern_key = false;
    $this->options = $options;
  }
  
  public function getComposite()
  {
    return $this->route;
  }
  
  public function getRegex()
  {
    if(!$this->formatted)
    {
      $this->format();
    }
    
    return $this->regex;
  }
  
  private function format()
  {
    $this->regex = $this->route->getRegex();
    $this->regex = str_replace("\n", '', $this->regex);
    $this->regex = str_replace('#^/', '#^', $this->regex);
    $this->regex = str_replace('$#x', '$#', $this->regex);
    $this->regex = str_replace('#', '', $this->regex);
            
    echo 'regex: '.$this->regex."\n";
    
    $this->formatPatternKeys();
    
    $this->formatted = true;
  }
  
  private function formatPatternKeys()
  {
    $found = preg_match_all('/\?P<([\w]*)>/', $this->regex, $match);
    
    if($found ==  0)
    {
      return;
    }
    
    $this->pattern_keys = array();    
    for($j=0; $j<$found; $j++)
    {
      $this->pattern_keys[] = $match[1][$j];
      $this->regex = str_replace($match[0][$j], '', $this->regex);
    }
    
    $this->has_pattern_key = true;
    echo "\t".'cleaned regex: '.$this->regex."\n";
  }
  
  protected function getPatternKeys()
  {
    if(!$this->has_pattern_key)
    {
      return '';
    }
    
    $array = array();
    $i = 1;
    foreach($this->pattern_keys as $key)
    {
      $array[] = $key.'=$'.$i++;
    }
    
    return '?'.implode('&', $array);
  }
  
  public function hasPatternKey()
  {
    return $this->has_pattern_key;
  }
  
  public function getPatternKeyAsArray()
  {
    return $this->pattern_keys;
  }
  
  abstract protected function getRuleOptions();
  abstract public function getRule($targetFile);
}
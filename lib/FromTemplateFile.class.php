<?php


class FromTemplateFile
{
  protected $template;
  protected $path;
  protected $array_parameter;
  
  public function __construct($path)
  {
    $this->path = $path;
  }
  
  public function loadTemplate($file)
  {
    if(!is_readable($file))
    {
      throw new FileNotFoundException($file);
    }
    
    $this->template = $file;
  }
  
    
  public function addReplacement($key, $value)
  {
    if(!$this->array_parameter) $this->array_parameter = array($key => $value);
    else $this->array_parameter[$key] = $value;
  }
  
  private function convertParameter()
  {
    $array_key = array();
    $array_value = array();
    
    foreach($this->array_parameter as $key => $value)
    {
      $array_key[] = $key;
      $array_value[] = $value;
    }
    
    return array($array_key, $array_value);
  }
  
    
  public function create()
  {
    if(!self::checkRootPath($this->path))
    {
      throw new Exception('Can not create subfolder for: '.$this->path);
    }
    
    if(!self::is_writable($this->path))
    {
      throw new Exception('Can not create this file: '.$this->path);
    }
    
    $str = file_get_contents($this->template);
    
    $this->addReplacement('###%DATETIME%###',     date('Y-m-d H:i:s'));
    $this->addReplacement('###%TEMPLATEPATH%###', $this->template);
    $this->addReplacement('###%FILEPATH%###',     $this->path);
    
    $array = $this->convertParameter();
    $str = str_replace($array[0], $array[1],  $str);
    
    file_put_contents($this->path, $str);
  }
  
  static public function is_writable($path) 
  {
    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
    {    
      return self::is_writable($path.uniqid(mt_rand()).'.tmp');
    }
    else if (is_dir($path))
    {
      return self::is_writable($path.'/'.uniqid(mt_rand()).'.tmp');
    }
    
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    
    $f = @fopen($path, 'a');
    if ($f===false)
    {
      return false;
    }
    fclose($f);
    
    if (!$rm)
    {
      unlink($path);
    }
    
    return true;
  }
  
  static function checkRootPath($path)
  {
    $rootpath = dirname($path);
    if(is_dir($rootpath))
    {
      return true;
    }
    elseif(mkdir($rootpath, 0777, true))
    { 
      return true;
    }
    else
    {
      return false;
    }
  }
}
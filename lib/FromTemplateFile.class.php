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
  
    
  public function create($rulesBlock)
  {
    if(!is_writable($this->path))
    {
      throw new FileNotFoundException($this->path);
    }
    
    $str = file_get_contents($this->template);
    
    $this->addReplacement('###%DATETIME%###',     date('Y-m-d H:i:s'));
    $this->addReplacement('###%TEMPLATEPATH%###', $this->template);
    $this->addReplacement('###%FILEPATH%###',     $this->path);
    
    $array = $this->convertParameter();
    $str = str_replace($array[0], $array[1],  $str);
    
    file_put_contents($this->path, $str);
  }
  
  function is_writable() 
  {

    if ($this->path{strlen($this->path)-1}=='/') // recursively return a temporary file path
    {    
      return is__writable($this->path.uniqid(mt_rand()).'.tmp');
    }
    else if (is_dir($this->path))
    {
      return is__writable($this->path.'/'.uniqid(mt_rand()).'.tmp');
    }
    
    // check tmp file for read/write capabilities
    $rm = file_exists($this->path);
    
    $f = @fopen($this->path, 'a');
    if ($f===false)
    {
      return false;
    }
    fclose($f);
    
    if (!$rm)
    {
      unlink($this->path);
    }
    
    return true;
  }
}
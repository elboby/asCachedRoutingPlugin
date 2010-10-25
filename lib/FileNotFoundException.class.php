<?php

Class FileNotFoundException extends Exception
{
  protected $filename;
  public function __construct($filename, $code = 0,  Exception $previous = NULL  )
  {
    $this->filename = $filename;
    
    parent::__construct(
       'This file can not be accessed (check if it exists or if the right are ok): '.$this->filename, 
       $code, 
       $previous
      );
  }
  
}
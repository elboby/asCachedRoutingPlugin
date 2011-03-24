<?php

class NginxGroupRewriteConfFile  extends FromTemplateFile
{
  public function loadRulesBlock($rulesBlock)
  {
    $locations = array();
    $locations_fallback = array();
    
    foreach($rulesBlock as $rule)
    {
      $re = '#rewrite \^/(?P<name>\w+)(.*)#';
      preg_match($re, $rule, $matches);
      if(isset($matches['name']))
      {
        //echo $matches['name']."\n";        
        $location_first = $matches['name'];
        if(isset($locations[$location_first]))
        {
          $locations[$location_first][] = $rule;
        }
        else
        {
          $locations[$location_first] = array();
          $locations[$location_first][] = $rule;
        }
      }
      else
      {
        //echo "---- not found: $rule\n";
        $locations_fallback[] = $rule;
      }
    }
    // var_dump($locations);
    // var_dump($locations_fallback);
    // die;
    
    $groupBlocks = '';
    foreach($locations as $loc => $rules)
    {
      $out = "location /$loc { \n".implode("\n",$rules)."\n}\n";
      $groupBlocks .= $out;
    }
    //var_dump($groupBlocks); die;
    
    $groupDefaultBlock = "\n".implode("\n",$locations_fallback)."\n";
    //var_dump($groupDefaultBlock); die;
    
    $this->addReplacement('###%RULES_TOKEN%###', $groupBlocks);
    $this->addReplacement('###%RULES_FALLBACK_TOKEN%###', $groupDefaultBlock);
  }
}
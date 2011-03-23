<?php

class NginxRewriteRuleFormatter extends RuleFormatter
{ 
  protected function getRuleOptions()
  {
    $rule_options = 'last;';
    if(isset($this->options['rule_options']))
    {
      $rule_options = $this->options['rule_options'];
    }
    return $rule_options;
  }
  
  public function getRule($targetFile)
  {
    $rw_rule = 'rewrite '.$this->getRegex().' /'.$targetFile.$this->getPatternKeys().' '.$this->getRuleOptions();
    if($this->debug) echo "\t".$rw_rule."\n\n";
    return $rw_rule;
  }
  
  public function getRegex()
  {
    $regex = parent::getRegex();
    $regex = str_replace('^', '^/', $regex);
    return $regex;
  }
  
}
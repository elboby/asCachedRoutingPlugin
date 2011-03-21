<?php

class ApacheModRewriteConfigFile extends FromTemplateFile
{ 
  public function loadRulesBlock($rulesBlock)
  {
    $this->addReplacement('###%RULES_TOKEN%###', $rulesBlock);
  }
}
<?php

class NginxRewriteConfFile extends FromTemplateFile
{ 
  public function loadRulesBlock($rulesBlock)
  {
    $this->addReplacement('###%RULES_TOKEN%###', $rulesBlock);
  }
}
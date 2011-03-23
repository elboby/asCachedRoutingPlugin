<?php
include(dirname(__FILE__).'/../bootstrap/unit.php');
require_once(sfConfig::get('sf_symfony_lib_dir').'/yaml/sfYaml.class.php');

//class to test
require_once(dirname(__FILE__).'/../../lib/RuleFormatter.class.php');
require_once(dirname(__FILE__).'/../../lib/webserver/NginxRewriteRuleFormatter.class.php');

$testCases = sfYaml::load(dirname(__FILE__).'/fixtures.yml');
 
$t = new lime_test(count($testCases), new lime_output_color());

$t->diag('asCachedRouting::getUrl');

foreach ($testCases as $case)
{ 
  $ar = array_keys($case['input']['rule']);
  $route_name = $ar[0];
  $targetFile = '_cache_routing/'.$route_name.'.php';
  $route_array = $case['input']['rule'][$route_name];
  
  $route = new sfRoute($route_array['url'], 
                       $route_array['param'], 
                       isset($route_array['requirements'])?$route_array['requirements']:array(),
                       isset($route_array['options'])?$route_array['options']:array()
                      );
                      
  $rf = new NginxRewriteRuleFormatter($route_name, $route, array('rule_options' => "last;"));
  $t->is($rf->getRegex(), $case['output']['regex'],'regex for: '.$case['comment']);
  $t->is($rf->getRule($targetFile), $case['output']['rewrite'], 'rewrite rule for: '.$case['comment']);
}
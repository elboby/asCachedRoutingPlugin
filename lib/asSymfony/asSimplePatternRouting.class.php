<?php

class asSimplePatternRouting extends sfPatternRouting
{
  /**
   * Finds a matching route for given URL.
   *
   * Returns false if no route matches.
   *
   * Returned array contains:
   *
   *  - name:       name or alias of the route that matched
   *  - route:      the actual matching route object
   *  - parameters: array containing key value pairs of the request parameters including defaults
   *
   * @param  string $url     URL to be parsed
   *
   * @return array|false  An array with routing information or false if no route matched
   */
  public function findRoute($url)
  {
    $rh = asRouteParameterHolder::getInstance();
    $name = $rh->getRouteName();
    $route = $this->routes[$name];
    $parameters = $rh->getParameters();

    if (isset($parameters['_star']))
    {
      $tmp = explode('/', $parameters['_star']);
      for ($i = 0, $max = count($tmp); $i < $max; $i += 2)
      {
        //dont allow a param name to be empty - #4173
        if (!empty($tmp[$i]))
        {
          $parameters[$tmp[$i]] = isset($tmp[$i + 1]) ? urldecode($tmp[$i + 1]) : true;
        }
      }
      unset($parameters['_star']);
    }
    
    return array('name' => $name, 'pattern' => $route->getPattern(), 'parameters' => $parameters);
  }
}

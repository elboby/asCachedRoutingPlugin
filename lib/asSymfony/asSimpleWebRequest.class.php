<?php

class asSimpleWebRequest extends sfWebRequest
{
  /**
   * Retrieves relative root url.
   *
   * @return string URL
   */
  public function getRelativeUrlRoot()
  {
    if (is_null($this->relativeUrlRoot))
    {
      $this->relativeUrlRoot = '';
    }

    return $this->relativeUrlRoot;
  }
}

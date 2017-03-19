<?php
/**
 * RequestIdBeforeMiddleware
 *
 * @package  [Application]
 */
#################################################################################################################################

namespace App\Middleware;

class RequestIdBeforeMiddleware extends \Nofuzz\Middleware
{
  /**
   * Let the Middleware do it's job
   *
   * @param  array  $args           URI parameters as key=value array
   * @return bool                   True=OK, False=Failed to handle it
   */
  function handle(array $args): bool
  {
    # Set requestId dependancy
    app()->container('requestId', md5(microtime(true)));

    return true;
  }

}

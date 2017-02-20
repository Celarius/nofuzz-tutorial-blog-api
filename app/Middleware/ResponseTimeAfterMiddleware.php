<?php
namespace App\Middleware;

class ResponseTimeAfterMiddleware extends \Nofuzz\Middleware
{
  /**
   * Let the Middleware do it's job
   *
   * @param  array  $args           URI parameters as key=value array
   * @return bool                   True=OK, False=Failed to handle it
   */
  function handle(array $args): bool
  {
    # Set "X-Response-Time" Header
    response()->setHeader('X-Response-Time', number_format(microtime(true) - ($_SERVER["REQUEST_TIME_FLOAT"] ?? 0),3,'.','') );

    return true;
  }

}

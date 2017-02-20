<?php
namespace App\Middleware;

class RequestIdAfterMiddleware extends \Nofuzz\Middleware
{
  /**
   * Let the Middleware do it's job
   *
   * @param  array  $args           URI parameters as key=value array
   * @return bool                   True=OK, False=Failed to handle it
   */
  function handle(array $args): bool
  {
    # Set "X-Request-Id" Header
    response()->setHeader('X-Request-Id', app('requestId'));

    return true;
  }

}

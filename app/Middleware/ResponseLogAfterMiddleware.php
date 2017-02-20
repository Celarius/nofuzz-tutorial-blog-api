<?php
namespace App\Middleware;

class ResponseLogAfterMiddleware extends \Nofuzz\Middleware
{
  /**
   * Let the Middleware do it's job
   *
   * @param  array  $args           URI parameters as key=value array
   * @return bool                   True=OK, False=Failed to handle it
   */
  function handle(array $args): bool
  {
    // As of PHP 5.4.0, REQUEST_TIME_FLOAT is available in the $_SERVER superglobal array.
    // It contains the timestamp of the start of the request with microsecond precision.

    # Log the request
    logger()->info(
      '"'.request()->getMethod().' '.request()->getUri().'" '.number_format(microtime(true) - ($_SERVER["REQUEST_TIME_FLOAT"] ?? 0),3,'.',''),
      [app('requestId')]
    );

    return true;
  }

}

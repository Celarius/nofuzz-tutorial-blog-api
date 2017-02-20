<?php
/**
 * CorsBeforeMiddleware
 *
 * @package  [Application]
 */
#################################################################################################################################

namespace App\Middleware;

class CorsBeforeMiddleware extends \Nofuzz\Middleware
{
  /**
   * Let the Middleware do it's job
   *
   * @param  array  $args           URI parameters as key=value array
   * @return bool                   True=OK, False=Failed to handle it
   */
  function handle(array $args): bool
  {
    # Get the Request CORS related headers
    $cors_origin = request()->getHeader('Origin')[0] ?? '';
    $cors_method = request()->getHeader('Access-Control-Request-Method')[0] ?? '';
    $cors_headers = request()->getHeader('Access-Control-Request-Headers');

    # Is this a CORS headers enabled request at all?
    if (empty($cors_origin) && empty($cors_method)) {
      # Nope, no CORS headers, so ok, nothing to do
      return true;
    }

    # Set the allowed condition (this is where custom code comes in)
    $CORS_allowed = true;

    if ($CORS_allowed) {
      # Always add the Allow-Origin
      response()
        ->setHeader('Access-Control-Allow-Origin', $cors_origin )
        ;

      # On OPTIONS requests, add the rest of the headers
      if (request()->getMethod()==='OPTIONS') {
        response()
          ->setHeader('Access-Control-Allow-Credentials', 'true' )
          ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH' )
          ->setHeader('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, *' )
          ->setHeader('Access-Control-Max-Age: 86400')
          ->setHeader('Timing-Allow-Origin', '*' )
          ;
      }
    }

    return $CORS_allowed;
  }

}

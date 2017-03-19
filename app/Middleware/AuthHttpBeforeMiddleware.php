<?php
/**
 * AuthHttpBeforeMiddleware
 *
 * Provides methods for authenticating requests using different
 * methods.
 *
 * Supported Authentication methds are:
 *   "Basic"
 *   "Apikey"
 *   "Bearer" (JWT tokens)
 *
 * Building JWT tokens: http://jwtbuilder.jamiekurtz.com/
 *
 * @package  [Application]
 */
#################################################################################################################################

namespace App\Middleware;

class AuthHttpBeforeMiddleware extends \Nofuzz\Middleware
{
  /**
   * Let the Middleware do it's job
   *
   * @param  array  $args           URI parameters as key=value array
   * @return bool                   True=OK, False=Failed to handle it
   */
  function handle(array $args): bool
  {
    # Params
    $authenticated = false;
    $username = '';
    $password = '';

    # Get the Authorization header
    $authorization = request()->getHeader('Authorization')[0] ?? '';

    # Decode into $type and $token
    $a = explode(' ',$authorization);
    $type = $a[0] ?? '';
    $token = $a[1] ?? '';

    # "Basic" Authentication ?
    if (strcasecmp($type,'Basic')==0) {
      list($username,$password) = explode(':',base64_decode($token));
      $authenticated = $this->authBasic($username,$password);

    } else
    # "Apikey" Authentication ?
    if (strcasecmp($type,'Apikey')==0) {
      $authenticated = $this->authApiKey($token);

    } else
    # "Bearer" Authentication (JWT) ?
    if (strcasecmp($type,'Bearer')==0) {
      $authenticated = $this->authBearer($token);

    } else {
      # Unknown authentication method
      response()
        ->setStatusCode(403);
    }

    # Failed ?
    if (!$authenticated) {
      response()
        ->setHeader('WWW-Authenticate', $type.' realm="'.(request()->getHeader('Host')[0] ?? '').'"' )
        ->setStatusCode(401);
    }

    return $authenticated;
  }


  /**
   * Basic authentication
   *
   * @param  string $username [description]
   * @param  string $password [description]
   * @return bool False for NOT authenticated, True for success
   */
  protected function authBasic(string $username, string $password)
  {
    $authenticated = false;

    #
    # Authenticate the Username & Password
    #
    // Custom authentication code goes here

    return $authenticated;
  }

  /**
   * Apikey authentication
   *
   * @param  string $apikey
   * @return bool False for NOT authenticated, True for success
   */
  protected function authApikey(string $apikey)
  {
    $authenticated = false;

    #
    # Authenticate the Apikey
    #
    // Custom authentication code goes here

    return $authenticated;
  }

  /**
   * Bearer authentication (JWT)
   *
   * @param  string $token
   * @return bool False for NOT authenticated, True for success
   */
  protected function authBearer(string $token)
  {
    $authenticated = false;

    # Get applications global secret
    $key = config()->get('application.secret');

    try {
      #
      # Authenticate the JWT payload
      #
      // Custom authentication code goes here

      # Verify the Token, and decode the payload - will throw exception on failure
      $payload = \Nofuzz\Helpers\JWT::decode($token, $key, ['HS256']);

      # Check that there is a valid payload, then we are authenticated
      if (!is_null($payload)) {
        # Store the Payload in the Dependency Container for later use in controller
        app()->container('jwt:payload',$payload);

        $authenticated = true;
      }

    } catch (\Exception $e) {
      # Decoding failed, invalid JWT payload
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'msg'=>$e->getMessage(),'trace'=>$e->getTraceAsString()]);

    }

    return $authenticated;
  }

}

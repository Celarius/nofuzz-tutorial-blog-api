<?php
/**
 * SignOutController.php
 *
 * This will just accept the JWT as it is and return 204.
 * In reality nothing gets deleted due to the nature of JWT tokens.
 *
 * If one wants, this can be extended to delete the actual token from a
 * cache or similar, and updating the Auth check to scan this cache for
 * valid tokens..
 */
#################################################################################################################################

namespace App\Controllers\v1;

class SignOutController extends \Nofuzz\Controller
{
  /**
   * DELETE handler
   *
   * @param  array  $args [description]
   * @return bool
   */
  public function handleDELETE(array $args)
  {
    # Get the JWT Payload
    $jwt = app()->container('jwt:payload');

    if (!$jwt) {
      response()
        ->errorJson(401,'','Invalid credentials');

      return true;
    }

    # Send an empty body as return
    response()
      ->setCacheControl('private, no-cache, no-store')
      ->setStatusCode( 204 ) // "No Content"
      ->setBody('');

    return true;
  }

}

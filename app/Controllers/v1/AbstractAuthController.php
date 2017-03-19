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
 *
 * @package  Nofuzz-Tutorial-Blog
 */
#################################################################################################################################

namespace App\Controllers\v1;

class AbstractAuthController extends \Nofuzz\Controller
{
  protected $jwt;       // JWToken payload
  protected $account;   // The account sending the request

 /**
   * Initialize Controller
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function initialize(array $args)
  {
    # Get the JWT Payload
    $this->jwt = app()->container('jwt:payload');

    # Safety check that we actually have a JWT
    if (!$this->jwt) {
      response()
        ->errorJson(401,'','Invalid credentials');

      return true;
    }

    # Fetch the associated account (should exist, unless we deleted it since issuing JWT)
    $account = (new \App\Db\BlogAccountDao())->fetchBy('uuid',$this->jwt->uuid);
    if ($account) {
      $this->account = $account;

      return parent::initialize($args);

    } else {
      response()
        ->errorJson(401,'','Invalid credentials. Token.uuid not found');
    }

    return parent::initialize($args);
  }

}

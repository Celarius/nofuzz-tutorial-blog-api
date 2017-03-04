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

class AccountsController extends \Nofuzz\Controller
{
  protected $jwt;       // JWToken payload
  protected $account;   // The account sending the request

  /**
   * All requests pass through here
   */
  public function handle(array $args)
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
    $accounts = (new \App\Db\AccountDao('blog_db'))->fetchByUuid($this->jwt->uuid);
    if (count($accounts)>0) {
      $this->account = $accounts[0];

      return parent::handle($args);
    }

    return false;
  }

  /**
   * GET handler
   *
   * @param  array  $args [description]
   * @return bool
   */
  public function handleGET(array $args)
  {
    $parId = $args['id'] ?? '';
    $parQ = queryParam('q');

    # Fetch all accounts
    $accounts = (new \App\Db\AccountDao('blog_db'))->fetchByKeywords(['id'=>$parId]);

    # Convert to array
    $data = [];
    foreach($accounts as $account) {
      $data[] = $account->asArray();
    }

    # Send an empty body as return
    response()
      ->setStatusCode( 200 )
      ->setJsonBody( $data );

    return true;
  }

  /**
   * POST handler
   *
   * @param  array  $args [description]
   * @return bool
   */
  public function handlePOST(array $args)
  {
    return false;
  }

  /**
   * PUT handler
   *
   * @param  array  $args [description]
   * @return bool
   */
  public function handlePUT(array $args)
  {
    return false;
  }

  /**
   * DELETE handler
   *
   * @param  array  $args [description]
   * @return bool
   */
  public function handleDELETE(array $args)
  {
    return false;
  }

}

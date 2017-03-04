<?php
/**
 * AccountsController.php
 *
 * CRUD methods for Accounts
 *
 * @package     Nofuzz-blog-tutorial
 */
#################################################################################################################################

namespace App\Controllers\v1;

class AccountsController extends \App\Controllers\v1\AbstractAuthController
{
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

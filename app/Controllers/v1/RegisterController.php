<?php
/**
 * RegisterController.php
 *
 * Attempts to register a new account in the system.
 * Verifies the parameters, and validates that there is no other account
 * with the same signin credentials.
 *
 * If successful adds the account to the database, and returns the information
 * for the account.
 *
 * Note:   This controller can be used to scrape for eamils/account names as it
 *         returns explicit info on which field is incorrect. In production a
 *         single message should be enough for both cases.
 *
 * Note2:  The registration endpoint should not be exposed to the public internet,
 *         instead a UI should handle the request/response from this endpoint and
 *         abstract the information.
 *
 * @package     Nofuzz-blog-tutorial
 */
#################################################################################################################################
/*
POST Method expects the following payload:

Content-Type: application/json
{
  "login_name":"adming",
  "first_name": "system",
  "last_name": "admin",
  "email": "adming@nofuzz.dev",
  "password": "admin"
}

Response is the structure:
{
  "id": null,
  "created_dt": "",
  "modified_dt": "",
  "uuid": "string",
  "login_name": "string",
  "first_name": "string",
  "last_name": "string",
  "email": "string",
  "jwt_secret": "string",
  "pw_salt": "string",
  "pw_hash": "string",
  "pw_iterations": null,
  "status": 0
}
*/
namespace App\Controllers\v1;

class RegisterController extends \Nofuzz\Controller
{
  /**
   * Handle POST
   *
   * @param  array  $args [description]
   * @return [type]       [description]
   */
  public function handlePOST(array $args)
  {
    # Validate HTTP Request "Content-type"
    if (!preg_match('/application\/json/i',(request()->getHeader('Content-Type')[0] ?? ''))) {
      response()->errorJson(400,'','Invalid Content-Type: '.(request()->getHeader('Content-Type')[0] ?? '') );

      return false;
    }

    # Decode payload
    $params = (json_decode(request()->getBody()->getContents(),true) ?? []);

    # Check input params are ok - stage 1
    if (!$this->checkParams($params)) {
      return true;
    }

    # Verify parameters - stage 2
    if (!$this->validateParams($params)) {
      return true;
    }

    # Create an account, prefill with values from array
    $account = new \App\Db\Account($params);
    $account->setUuid( \Nofuzz\Helpers\UUID::generate() );
    $account->setPwSalt( \Nofuzz\Helpers\Hash::generate($account->getUuid()) );
    $account->setPwHash( \Nofuzz\Helpers\Hash::generate($account->getUuid().$params['password']) );

    # Insert into DB
    if ((new \App\Db\AccountDao('blog_db'))->insert($account)) {
      # Generate Response
      response()
        ->setCacheControl('private, no-cache, no-store')
        ->setStatusCode( 200 )
        ->setJsonBody( $account->asArray() );

    } else {
      # Generate Response
      response()
        ->errorJson(501,'','Failed to register account, reason unknown' );

    }

    return true;
  }


  /**
   * Checks the params
   *
   * @param  array  $params [description]
   * @return bool
   */
  protected function checkParams(array $params=[]):bool
  {
    # login
    if (empty($params['login_name'])) {
      response()->errorJson(400,'','Parameter {login} can not be empty');

      return false;
    }

    # firstname & lastname & email
    if (empty($params['first_name']) || empty($params['last_name']) || empty($params['email'])) {
      response()->errorJson(400,'','Parameters {first_name}, {last_name} or {email} can not be empty');

      return false;
    }

    # password
    if (empty($params['password'])) {
      response()->errorJson(400,'','Parameters {password} can not be empty');

      return false;
    }

    return true;
  }


  /**
   * Validates the params
   *
   * @param  array  $params [description]
   * @return bool
   */
  protected function validateParams(array $params=[]):bool
  {
    $dbAccounts = (new \App\Db\AccountDao('blog_db'))->fetchByLoginName($params['login_name']);

    if (count($dbAccounts)>0) {
      response()->errorJson(400,'','An account with {login} already exists');
      return false;
    }

    $dbAccounts = (new \App\Db\AccountDao('blog_db'))->fetchByEMail($params['email']);
    if (count($dbAccounts)>0) {
      response()->errorJson(400,'','An account with {email} already exists');
      return false;
    }

    return true;
  }

}

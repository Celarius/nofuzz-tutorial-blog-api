<?php
/**
 * SignInController.php
 *
 * Authenticates an account based on {login_name} or {email} and {password}.
 * Generates a JWT response if successful, that can be used in successive
 * API calls.
 *
 * @package  Nofuzz-Tutorial-Blog
 */
#################################################################################################################################
/*
POST Method expects the following payload:

Content-Type: application/json
{
  "login_name":"admin",
  "email": "adminn@domain.com",
  "password": "admin"
}

Successful Response:
{
  "result": "success",
  "token": "...JWT token..."
}

*/
namespace App\Controllers\v1;

class SignInController extends \Nofuzz\Controller
{

  public function handlePOST(array $args)
  {
    # Validate Content-type
    if (!preg_match('/application\/json/i',(request()->getHeader('Content-Type')[0] ?? ''))) {
      response()->errorJson(400,'','Invalid Content-Type: '.(request()->getHeader('Content-Type')[0] ?? '') );

      return false;
    }

    # Decode payload
    $json = request()->getBody()->getContents();
    $params = (json_decode($json,true) ?? []);

    # Check input params are ok - stage 1
    if (!$this->checkParams($params)) {

      return true;
    }

    # Load the Account based on LOGIN_NAME or EMAIL
    if (!empty($params['login_name'])) {
      # Load the account specific by "login_name"
      $account = (new \App\Db\BlogAccountDao('blog_db'))->fetchByLoginName($params['login_name']);
    } else
    if (!empty($params['email'])) {
      # Load the account specific by "email"
      $account = (new \App\Db\BlogAccountDao('blog_db'))->fetchByEMail($params['email']);
    }

    if ($account) {
      # Verify password
      $pwHash = \Nofuzz\Helpers\Hash::generate($account->getUuid().$params['password']);

      # Compare password hashes
      if ( strcmp($pwHash, $account->getPwHash())==0 ) {

        $payload['uuid'] = $account->getUuid();
        $payload['first_name'] = $account->getFirstName();
        $payload['last_name'] = $account->getLastName();

        $payload['iat'] = time();        // now in UTC
        $payload['exp'] = time() + 3600; // 1 hour expiration time

        $key = config()->get('application.secret');
        $jwtToken = \Nofuzz\Helpers\Jwt::encode($payload, $key, 'HS256', null, $headers);

        $response['result'] = 'success';
        $response['token'] = $jwtToken;

        response()
          ->setCacheControl('private, no-cache, no-store')
          ->setStatusCode( 200 )
          ->setJsonBody( $response );

        return true;
      }
    }

    response()
      ->errorJson(401,'','Unknown username or invalid password');

    return true;
  }


  /**
   * Checks the params
   *
   * @param  array  $params   Request JSON document as array
   * @return bool             True for successfuly check, False if something is wrong
   */
  protected function checkParams(array $params=[]):bool
  {
    # No payload?
    if (count($params)==0) {
      response()->errorJson(400,'','No payload provided');

      return false;
    }

    # login
    if (empty($params['login_name']) && empty($params['email'])) {
      response()->errorJson(400,'','Parameter {login} or {email} can not be empty');

      return false;
    }

    # password
    if (empty($params['password'])) {
      response()->errorJson(400,'','Parameters {password} can not be empty');

      return false;
    }

    return true;
  }

}

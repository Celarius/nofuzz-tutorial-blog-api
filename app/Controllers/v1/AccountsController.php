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
    $accounts = (new \App\Db\BlogAccountDao('blog_db'))->fetchByKeywords(['id'=>$parId]);

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
    $body = $this->decodeBody();

    if (!$body)
      return true;

    # Check & verify login_name
    if (empty($body['login_name'])) {
        response()
          ->errorJson(400,'','Account must have {login_name}');

        return true;
    } else {
      $acc = (new \App\Db\BlogAccountDao('blog_db'))->fetchByLoginName($body['login_name']);
      if ($acc) {
        response()
          ->errorJson(400,'','Account already exists with {login_name}');

        return true;
      }
    }

    # Check & verify email
    if (empty($body['email'])) {
        response()
          ->errorJson(400,'','Account must have {email}');

        return true;
    } else {
      $acc = (new \App\Db\BlogAccountDao('blog_db'))->fetchByEmail($body['email']);
      if ($acc) {
        response()
          ->errorJson(400,'','Account already exists with {email}');

        return true;
      }
    }

    # Check & verify password
    if (empty($body['password'])) {
        response()
          ->errorJson(400,'','Account must have a {password}');

        return true;
    }

    # Create a new ccount
    $account = new \App\Db\BlogAccount( $body );

    # Overwrite a few params, calculate password hash etc.
    $account->setUuid( \Nofuzz\Helpers\UUID::generate() );
    $account->setPwSalt( \Nofuzz\Helpers\Hash::generate($account->getUuid()) );
    $account->setPwHash( \Nofuzz\Helpers\Hash::generate($account->getUuid().$body['password']) );

    # Insert into DB
    if ((new \App\Db\BlogAccountDao('blog_db'))->insert($account)) {
      # Generate Response
      response()
        ->setCacheControl('private, no-cache, no-store')
        ->setStatusCode( 200 )
        ->setJsonBody( $account->asArray() );

    } else {
      # Generate Response
      response()
        ->errorJson(500,'','Failed to register account, reason unknown' );

    }

    return true;
  }

  /**
   * PUT handler
   *
   * @param  array  $args [description]
   * @return bool
   */
  public function handlePUT(array $args)
  {
    $body = $this->decodeBody();

    if (!$body)
      return true;

    # Check & verify password
    if (empty($body['uuid'])) {
        response()
          ->errorJson(400,'','Must provide a valid {uuid}');

        return true;
    }

    # Fetch the Account based on UUID
    $account = (new \App\Db\BlogAccountDao('blog_db'))->fetchByUuid($body['uuid']);

    # Check & verify login_name - if present & different
    if (!empty($body['login_name']) && strcasecmp($body['login_name'],$account->getLoginName())!=0 ) {
      $acc = (new \App\Db\BlogAccountDao('blog_db'))->fetchByLoginName($body['login_name']);
      if ($acc) {
        response()
          ->errorJson(400,'','Account already exists with {login_name}');

        return true;
      }
    }

    # Check & verify email - if present
    if (!empty($body['email']) && strcasecmp($body['email'],$account->getEmail())!=0 ) {
      $acc = (new \App\Db\BlogAccountDao('blog_db'))->fetchByEmail($body['email']);
      if ($acc) {
        response()
          ->errorJson(400,'','Account already exists with {email}');

        return true;
      }
    }

    # Check & verify password
    if (empty($body['password'])) {
        response()
          ->errorJson(400,'','Account must have a {password}');

        return true;
    }


    # Update the fields
    if (!empty($body['login_name'])) $account->setLoginName($body['login_name']);
    if (!empty($body['first_name'])) $account->setFirstName($body['first_name']);
    if (!empty($body['last_name'])) $account->setLastName($body['last_name']);
    if (!empty($body['email'])) $account->setEMail($body['email']);
    if (!empty($body['password'])) {
      $account->setPwSalt( \Nofuzz\Helpers\Hash::generate($account->getUuid()) );
      $account->setPwHash( \Nofuzz\Helpers\Hash::generate($account->getUuid().$body['password']) );
    }

    # Update into DB
    if ((new \App\Db\BlogAccountDao('blog_db'))->update($account)) {
      # Generate Response
      response()
        ->setCacheControl('private, no-cache, no-store')
        ->setStatusCode( 200 )
        ->setJsonBody( $account->asArray() );

    } else {
      # Generate Response
      response()
        ->errorJson(500,'','Failed to update account, reason unknown' );

    }

    return true;
  }

  /**
   * DELETE handler
   *
   * @param  array  $args [description]
   * @return bool
   */
  public function handleDELETE(array $args)
  {
    $account = null;

    # Check if /{id} given, if so load the account
    $parId = $args['id'];
    if (!empty($parId)) {
      $account = (new \App\Db\BlogAccountDao('blog_db'))->fetchById($parId);

    } else {
      # Else check if there was a QueryParam ?uuid=<uuid>, and load account based on that
      $uuid = queryParam('uuid');
      $account = (new \App\Db\BlogAccountDao('blog_db'))->fetchByUuid($uuid);

    }

    # Make sure we have an account
    if (!$account) {
        response()
          ->errorJson(400,'','Must provide a valid {id} or {uuid}');

        return true;
    }

    if ((new \App\Db\BlogAccountDao('blog_db'))->delete($account)) {
      response()
        ->setCacheControl('private, no-cache, no-store')
        ->setStatusCode( 204 )
        ->setBody();

    } else {
      response()
        ->errorJson(500,'','Could not delete account');

    }

    return true;
  }


  /**
   * Decode the Body, checking that header is correct also
   *
   * @return Body as array | null
   */
  protected function decodeBody()
  {
    # Validate HTTP Request "Content-type"
    if (!preg_match('/application\/json/i',(request()->getHeader('Content-Type')[0] ?? ''))) {
      response()->errorJson(400,'','Invalid Content-Type: '.(request()->getHeader('Content-Type')[0] ?? '') );

      return null;
    }

    # Decode payload
    $body = (json_decode(request()->getBody()->getContents(),true) ?? []);
    if (count($body)==0) {
      response()->errorJson(400,'','Invalid {body}');

      return null;
    }

    return $body;
  }

}

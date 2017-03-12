<?php
/**
 * BlogAccountController.php
 *
 *    Controller for table blog_accounts
 *
 *  Generated with DaoGen v0.4.3
 *
 * @since    2017-03-10 19:24:29
 * @package  Nofuzz Appliction
 */
#########################################################################################
/*
JSON Model:
{
  "id": 0,
  "created_dt": "1970-01-01 00:00:00",
  "modified_dt": "1970-01-01 00:00:00",
  "uuid": "",
  "login_name": "",
  "first_name": "",
  "last_name": "",
  "email": "",
  "jwt_secret": "",
  "pw_salt": "",
  "pw_hash": "",
  "pw_iterations": 0,
  "status": 0
}
*/
#########################################################################################

namespace App\Controllers\v1;

class BlogAccountController extends \Nofuzz\Controller
{
  /**
   * Handle GET requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handleGET(array $args)
  {
    $parUuid = $args['uuid'] ?? null;   // Get path provided {uuid}

    if (!empty($parUuid)) {
      $items = (new \App\Db\BlogAccountDao('blog_db'))->fetchByUuid($parUuid);
    } else {
      $items = (new \App\Db\BlogAccountDao('blog_db'))->fetchAll();
    }

    $data = [];
    foreach ($items as $item)
      $data[] = $item->asArray();

    response()
      ->setStatusCode( 200 )
      ->setJsonBody( $data );

    return true;
  }

  /**
   * Handle POST requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handlePOST(array $args)
  {
    # Validate HTTP Request "Content-type"
    if (!preg_match('/application\/json/i',(request()->getHeader('Content-Type')[0] ?? ''))) {
      response()
        ->errorJson(400,'','Invalid Content-Type: '.(request()->getHeader('Content-Type')[0] ?? '') );
      return true;
    }

    # Decode payload
    $body = (json_decode(request()->getBody()->getContents(),true) ?? []);
    if (count($body)==0) {
      response()
        ->errorJson(400,'','Invalid {body}');

      return true;
    }

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

    # Create new item
    $item = new \App\Db\BlogAccount($body);
    $item->setUuid( \Nofuzz\Helpers\UUID::generate() );
    $item->setPwSalt( \Nofuzz\Helpers\Hash::generate($item->getUuid()) );
    $item->setPwHash( \Nofuzz\Helpers\Hash::generate($item->getUuid().$body['password']) );

    # Insert into DB
    $ok = (new \App\Db\BlogAccountDao('blog_db'))->insert($item);

    if ($ok) {
      # Generate Response
      response()
        ->setCacheControl('private, no-cache, no-store')
        ->setStatusCode( 200 )
        ->setJsonBody( $item->asArray() );

    } else {
      # Generate Response
      response()
        ->errorJson(500,'','Failed to register account, reason unknown' );

    }

    return true;
  }

  /**
   * Handle PUT requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handlePUT(array $args)
  {
    // Note: ANY authenticated user can update accounts
    //       This is intentional as the Accounts API should not be exposed to the public,
    //       but a UI instead acecssible only by System Admins.

    # Validate HTTP Request "Content-type"
    if (!preg_match('/application\/json/i',(request()->getHeader('Content-Type')[0] ?? ''))) {
      response()
        ->errorJson(400,'','Invalid Content-Type: '.(request()->getHeader('Content-Type')[0] ?? '') );
      return true;
    }

    # Decode payload
    $body = (json_decode(request()->getBody()->getContents(),true) ?? []);
    if (count($body)==0) {
      response()
        ->errorJson(400,'','Invalid {body}');

      return true;
    }

    # Check & verify UUID
    if (empty($body['uuid'])) {
        response()
          ->errorJson(400,'','Must provide a valid {uuid}');

        return true;
    }

    # Fetch the Account based on UUID
    $item = (new \App\Db\BlogAccountDao('blog_db'))->fetchByUuid($body['uuid']);

    # Check & verify login_name - if present & different
    if (!empty($body['login_name']) && strcasecmp($body['login_name'],$item->getLoginName())!=0 ) {
      $acc = (new \App\Db\BlogAccountDao('blog_db'))->fetchByLoginName($body['login_name']);
      if ($acc) {
        response()
          ->errorJson(400,'','Account already exists with {login_name}');

        return true;
      }
    }

    # Check & verify email - if present
    if (!empty($body['email']) && strcasecmp($body['email'],$item->getEmail())!=0 ) {
      $acc = (new \App\Db\BlogAccountDao('blog_db'))->fetchByEmail($body['email']);
      if ($acc) {
        response()
          ->errorJson(400,'','Account already exists with {email}');

        return true;
      }
    }

    # Update the fields
    if (!empty($body['login_name'])) $item->setLoginName($body['login_name']);
    if (!empty($body['first_name'])) $item->setFirstName($body['first_name']);
    if (!empty($body['last_name'])) $item->setLastName($body['last_name']);
    if (!empty($body['email'])) $item->setEMail($body['email']);
    if (!empty($body['password'])) {
      $item->setPwSalt( \Nofuzz\Helpers\Hash::generate($item->getUuid()) );
      $item->setPwHash( \Nofuzz\Helpers\Hash::generate($item->getUuid().$body['password']) );
    }

    $ok = (new \App\Db\BlogAccountDao('blog_db'))->update($item);

    # Update into DB
    if ($ok) {
      # Generate Response
      response()
        ->setCacheControl('private, no-cache, no-store')
        ->setStatusCode( 200 )
        ->setJsonBody( $item->asArray() );

    } else {
      # Generate Response
      response()
        ->errorJson(500,'','Failed to update account, reason unknown' );

    }

    return true;
  }

  /**
   * Handle DELETE requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handleDELETE(array $args)
  {
    // Note: ANY authenticated user can delete accounts
    //       This is intentional as the Accounts API should not be exposed to the public,
    //       but a UI instead acecssible only by System Admins.

    $parUuid = $args['uuid'];

    $item = (new \App\Db\BlogAccountDao('blog_db'))->fetchByUuid($parUuid);

    if ($item) {
      $ok = (new \App\Db\BlogAccountDao('blog_db'))->delete($item);
    }

    response()
      ->setStatusCode( 204 )
      ->setBody('');

    return true;
  }

}


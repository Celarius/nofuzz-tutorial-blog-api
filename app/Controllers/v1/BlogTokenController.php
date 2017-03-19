<?php
/**
 * BlogTokenController.php
 *
 *    Controller for table blog_tokens
 *
 *  Generated with DaoGen v0.4.8
 *
 * @since    2017-03-18 21:42:54
 * @package  Nofuzz Appliction
 */
#########################################################################################
/*
JSON Model:
{
  "id": 0,
  "created_dt": "1970-01-01 00:00:00",
  "modified_dt": "1970-01-01 00:00:00",
  "sessionid": "",
  "account_id": 0,
  "expires_dt": "1970-01-01 00:00:00",
  "status": 0
}
*/
#########################################################################################

namespace App\Controllers\v1;

class BlogTokenController extends \Nofuzz\Controller
{
 /**
   * Initialize Controller
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function initialize(array $args)
  {
    return parent::initialize($args);
  }

  /**
   * Handle GET requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handleGET(array $args)
  {
    $parUuid = $args['uuid']

    if (!empty($parUuid)) {
      $item = (new \App\Db\BlogTokenDao())->fetchBy('uuid',$parUuid);
      if ($item) $items[] = $item;
    } else {
      $items = (new \App\Db\BlogTokenDao())->fetchAll();
    }

    $data = [];    foreach ($items as $item)
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
      return null;
    }

    // Should check for existance of $item already in DB and abort if found
    // Ex. using:
    //   $item = (new \App\Db\BlogTokenDao())->fetchBy('id',$id);

    # Create new Item, set properties
    $item = new \App\Db\BlogToken($body);
    // $item->setId(0);
    // $item->setCreatedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    // $item->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    // $item->setSessionid('');
    // $item->setAccountId(0);
    // $item->setExpiresDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    // $item->setStatus(0);

    $ok = (new \App\Db\BlogTokenDao())->insert($item);

    response()
      ->setStatusCode( 200 )
      ->setJsonBody( $item->asArray() );

    return true;
  }

  /**
   * Handle PUT requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handlePUT(array $args)
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
      return null;
    }

    $item = new \App\Db\BlogToken($body);

    // Should check that all parameters/properties are correct in $item

    $ok = (new \App\Db\BlogTokenDao())->update($item);

    response()
      ->setStatusCode( 200 )
      ->setJsonBody( $item->asArray() );

    return true;
  }

  /**
   * Handle DELETE requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handleDELETE(array $args)
  {
    $parUuid = $args['uuid'];

    // Should check Authorization to perform delete of $item

    $item = (new \App\Db\BlogTokenDao())->fetchBy('uuid',$parUuid);
    if ($item) {
      $ok = (new \App\Db\BlogTokenDao())->delete($item);
    }

    response()
      ->setStatusCode( 204 )
      ->setBody('');

    return true;
  }

}


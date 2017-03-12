<?php
/**
 * BlogBlogController.php
 *
 *    Controller for table blog_blogs
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
  "account_id": 0,
  "title": "",
  "description": "",
  "status": 0
}
*/
#########################################################################################

namespace App\Controllers\v1;

class BlogBlogController extends \Nofuzz\Controller
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
    $parUuid = $args['uuid'];

    if (!empty($parUuid)) {
      $items = (new \App\Db\BlogBlogDao('blog_db'))->fetchByUuid($parUuid);
    } else {
      $items = (new \App\Db\BlogBlogDao('blog_db'))->fetchAll();
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

    # Fetch the users account
    $jwt = app()->container('jwt:payload');
    $account = (new \App\Db\BlogAccountDao('blog_db'))->fetchByUuid( $jwt->uuid );

    if (!$account) {
      response()
        ->errorJson(400,'','Invalid {account}, something is seriously wrong...');

      return true;
    }

    $item = new \App\Db\BlogBlog($body);
    $item->setAccountId( $account->getId() );
    $item->setUuid( \Nofuzz\Helpers\UUID::generate() );

    $ok = (new \App\Db\BlogBlogDao('blog_db'))->insert($item);

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

    # Fetch the users account
    $jwt = app()->container('jwt:payload');
    $account = (new \App\Db\BlogAccountDao('blog_db'))->fetchByUuid( $jwt->uuid );

    if (!$account) {
      response()
        ->errorJson(400,'','Invalid {account}, something is seriously wrong...');

      return true;
    }

    # Fetch the Blog Item we are updating account
    $item = (new \App\Db\BlogBlogDao('blog_db'))->fetchByUuid($body['uuid']);
    if (!$item) {
      response()
        ->errorJson(404,'','Blog {blog.uuid} not found');

      return true;
    }

    # Check the Blog belongs to the Account - Output as 404 if not owned by account
    if ( $item->getAccountId()<>$account->getId() ) {
      response()
        ->errorJson(404,'','Blog {blog.uuid} not found');

      return true;
    }

    # Update the fields & Update in DB
    $item->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    if (!empty($body['title'])) $item->setTitle($body['title']);
    if (!empty($body['description'])) $item->setDescription($body['description']);

    $ok = (new \App\Db\BlogBlogDao('blog_db'))->update($item);

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
   * Handle DELETE requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handleDELETE(array $args)
  {
    $parUuid = $args['uuid'];

    # Fetch the users account
    $jwt = app()->container('jwt:payload');
    $account = (new \App\Db\BlogAccountDao('blog_db'))->fetchByUuid( $jwt->uuid );

    if (!$account) {
      response()
        ->errorJson(400,'','Invalid {account}, something is seriously wrong...');

      return true;
    }

    # Check the Item belongs to the Account - Output as 404 if not owned by account
    if ( $item->getAccountId()<>$account->getId() ) {
      response()
        ->errorJson(404,'','Blog {blog.uuid} not found');

      return true;
    }

    # Fetch the Item
    $item = (new \App\Db\BlogBlogDao('blog_db'))->fetchByUuid($parUuid);

    if ($item) {
      $ok = (new \App\Db\BlogBlogDao('blog_db'))->delete($item);
    }

    response()
      ->setStatusCode( 204 )
      ->setBody('');

    return true;
  }

}


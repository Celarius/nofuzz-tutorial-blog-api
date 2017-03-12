<?php
/**
 * BlogCommentController.php
 *
 *    Controller for table blog_comments
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
  "article_id": 0,
  "account_id": 0,
  "comment": "",
  "status": 0
}
*/
#########################################################################################

namespace App\Controllers\v1;

class BlogCommentController extends \Nofuzz\Controller
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
    $parId = $args['id'] ?? null;   // Get path provided {id}

    if (!empty($parId)) {
      $items = (new \App\Db\BlogCommentDao('blog_db'))->fetchById($parId);
    } else {
      $items = (new \App\Db\BlogCommentDao('blog_db'))->fetchAll();
    }

    $data = [];    foreach ($items as $item) $data[] = $item->asArray();
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

    $item = new \App\Db\BlogComment($body);

    // Should check for existance of $item already in DB and abort if found

    $ok = (new \App\Db\BlogCommentDao('blog_db'))->insert($item);

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

    $item = new \App\Db\BlogComment($body);

    // Should check that all parameters/properties are correct in $item

    $ok = (new \App\Db\BlogCommentDao('blog_db'))->update($item);

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
    $parId = $args['id'] ?? null;   // Get path provided {id}

    $item = (new \App\Db\BlogCommentDao('blog_db'))->fetchById($parId);
    if ($item) {
      $ok = (new \App\Db\BlogCommentDao('blog_db'))->delete($item);
    }

    response()
      ->setStatusCode( 204 )
      ->setBody('');

    return true;
  }

}


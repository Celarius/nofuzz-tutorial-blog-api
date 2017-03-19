<?php
/**
 * BlogCommentController.php
 *
 *    Controller for table blog_comments
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
    $parUuid = $args['uuid'];

    if (!empty($parUuid)) {
      $item = (new \App\Db\BlogCommentDao())->fetchBy('uuid',$parUuid);
      if ($item) $items[] = $item;
    } else {
      $items = (new \App\Db\BlogCommentDao())->fetchAll();
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
    $parUuid = $args['uuid']; // Article's UUID to which the comment belongs

    # Check & verify UUID
    if (empty($parUuid)) {
        response()
          ->errorJson(400,'','Invalid {uuid}');

        return true;
    }

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

    # Get Article UUID we want to post a comment to
    $article = (new \App\Db\BlogArticleDao())->fetchBy('uuid',$parUuid);

    if ( $article ) {
      response()
        ->errorJson(400,'','Invalid {uuid}');

      return true;
    }

    # Create new Item, set properties
    $item = new \App\Db\BlogComment($body);
    $item->setCreatedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $item->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $item->setUuid( \Nofuzz\Helpers\UUID::v4() );
    $item->setArticleId( $article->getId() );
    $item->setAccountId( $this->account->getId() );
    $item->setStatus(0);
    $ok = (new \App\Db\BlogCommentDao())->insert($item);

    if ($ok) {
      response()
        ->setCacheControl('private, no-cache, no-store')
        ->setStatusCode( 200 )
        ->setJsonBody( $item->asArray() );

    } else {
      response()
        ->errorJson(500,'','Operation failed, reason unknown' );

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
    $parUuid = $args['uuid']; // Comments's UUID

    # Check & verify UUID
    if (empty($parUuid)) {
        response()
          ->errorJson(400,'','Invalid {uuid}');

        return true;
    }

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

    # Get original comment & verify it's our's
    $item = (new \App\Db\BlogCommentDao())->fetchByKeywords(['uuid'=>$parUuid,'account_id'=>$this->account->getId()]);
    if ( !$item ) {
      response()
        ->errorJson(400,'','Invalid {uuid}');

      return true;
    }

    # Set properties & update
    $item->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    if (isset($body['comment'])) $item->setComment($body['comment']);
    if (isset($body['status'])) $item->setStatus($body['status']);
    $ok = (new \App\Db\BlogCommentDao())->update($item);

    if ($ok) {
      response()
        ->setCacheControl('private, no-cache, no-store')
        ->setStatusCode( 200 )
        ->setJsonBody( $item->asArray() );

    } else {
      response()
        ->errorJson(500,'','Operation failed, reason unknown' );

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

    # Get Comment & verify it's our's
    $item = (new \App\Db\BlogCommentDao())->fetchByKeywords(['uuid'=>$parUuid,'account_id'=>$this->account->getId()]);
    if ( !$item ) {
      response()
        ->errorJson(400,'','Invalid {uuid}');

      return true;
    }

    # Delete comment
    $ok = (new \App\Db\BlogCommentDao())->delete($item);

    response()
      ->setStatusCode( 204 )
      ->setBody('');

    return true;
  }

}


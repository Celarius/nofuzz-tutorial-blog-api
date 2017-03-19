<?php
/**
 * BlogBlogController.php
 *
 *    Controller for table blog_blogs
 *
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

class BlogBlogController extends \App\Controllers\v1\AbstractAuthController
{
  /**
   * Handle GET requests
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handleGET(array $args)
  {
    $parUuid = $args['uuid'];

    if (!empty($parUuid)) {
      $item = (new \App\Db\BlogBlogDao())->fetchBy('uuid',$parUuid);
      if ($item) $items[]=$item;
    } else {
      $items = (new \App\Db\BlogBlogDao())->fetchAll();
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
   *   Inserts a new Blog into the database
   *
   *   Model fields used:
   *     Title
   *     Description
   *     Status
   *
   *   Checks performed:
   *   1.  Validate HTTP Header Content-Type
   *   2.  Validate Body as valid JSON
   *   3.  Validate Title is not empty
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

    # Validate Title is not empty
    if ( empty($body['title']) ) {
      response()
        ->errorJson(404,'','Blog {title} can not be empty');

      return true;
    }

    # Create, Initialize and insert the Item
    $item = new \App\Db\BlogBlog($body);
    $item->setCreatedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $item->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $item->setAccountId( $this->account->getId() );
    $item->setUuid( \Nofuzz\Helpers\UUID::generate() );
    $ok = (new \App\Db\BlogBlogDao())->insert($item);

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
   *   Updates a Blog item in the database
   *
   *   Model fields used:
   *     Title
   *     Description
   *     Status
   *
   *   Checks performed:
   *   1.  Validate HTTP Header Content-Type
   *   2.  Validate Body as valid JSON
   *   3.  Validate Blog item with UUID exists
   *   4.  Validate Blog is owned by Authenticated account
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handlePUT(array $args)
  {
    $parUuid = $args['uuid']; // Blogs's UUID to update

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

      return true;
    }

    # Fetch the Blog Item we are updating & Check the Blog belongs to the Account
    $item = (new \App\Db\BlogBlogDao())->fetchByKeywords( ['uuid'=>$parUuid,'account_id'=>$this->account->getId()] );
    if (!$item) {
      response()
        ->errorJson(400,'','Invalid {uuid}');

      return true;
    }

    # Update the fields & Update in DB
    $item->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    if (!empty($body['title'])) $item->setTitle($body['title']);
    if (!empty($body['description'])) $item->setDescription($body['description']);
    $ok = (new \App\Db\BlogBlogDao())->update($item);

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
   *   Delete a Blog item from the database
   *
   *   Checks performed:
   *   1.  Validate UUID is not empty
   *   2.  Validate Blog is owned by Authenticated account
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handleDELETE(array $args)
  {
    $parUuid = $args['uuid'];

    # Validate UUID is not empty
    if (empty($parUuid)) {
      response()
        ->errorJson(400,'','Parameter {uuid} is invalid');

      return true;
    }

    # Fetch the Blog
    $blog = (new \App\Db\BlogBlogDao())->fetchByKeywords(['uuid'=>$parUuid,'account_id'=>$this->account->getId()])[0] ?? null;
    if ( !$blog ) {
      response()
        ->errorJson(404,'','Parameter {uuid} is invalid');

      return true;
    }

    # Delete it
    $ok = (new \App\Db\BlogBlogDao())->delete($blog);

    response()
      ->setStatusCode( 204 )
      ->setBody('');

    return true;
  }

}


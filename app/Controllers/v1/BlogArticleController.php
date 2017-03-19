<?php
/**
 * BlogArticleController.php
 *
 *    Controller for table blog_articles
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
  "blog_id": 0,
  "title": "",
  "body": "",
  "status": 0
}
*/
#########################################################################################

namespace App\Controllers\v1;

class BlogArticleController extends \App\Controllers\v1\AbstractAuthController
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
      $item = (new \App\Db\BlogArticleDao())->fetchBy('uuid',$parUuid);
      if ($item) $items[]=$item;
    } else {
      $items = (new \App\Db\BlogArticleDao())->fetchAll();
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
   *   Inserts a new Article under a Blog into the database
   *
   *   Model fields used:
   *     blog_id
   *     Title
   *     Body
   *     Status
   *
   *   Checks performed:
   *   1.  Validate HTTP Header Content-Type
   *   2.  Validate Body as valid JSON
   *   3.  Validate blog_id is not empty
   *   4.  Validate blog_id belongs to authenticated account
   *   5.  Validate title is not empty
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

    # Validate Blog_id is not empty
    if ( empty($body['blog_id']) ) {
      response()
        ->errorJson(404,'','Parameter {blog_id} can not be empty');

      return true;
    }

    # Fetch the Blog (belonging to Account)
    $blog = ((new \App\Db\BlogBlogDao())->fetchByKeywords(['blog_id'=>$body['blog_id'],'account_id'=>$this->account->getId()])[0] ?? null);
    if ( !$blog ) {
      response()
        ->errorJson(404,'','Parameter {blog_id} is invalid');

      return true;
    }

    # Validate Title is not empty
    if ( empty($body['title']) ) {
      response()
        ->errorJson(404,'','Article {title} can not be empty');

      return true;
    }

    # Create, Initialize and store the Article
    $item = new \App\Db\BlogArticle($body);
    $item->setCreatedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $item->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $item->setUuid( \Nofuzz\Helpers\UUID::v4() );
    $item->setBlogId( $blog->getId() );
    $ok = (new \App\Db\BlogArticleDao())->insert($item);

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
   *   Model fields used:
   *     uuid
   *     title
   *     body
   *     status
   *
   *   Checks performed:
   *   1.  Validate HTTP Header Content-Type
   *   2.  Validate Body as valid JSON
   *   3.  Validate uuid is not empty
   *   4.  Validate blog_id belongs to authenticated account
   *   5.  Validate title is not empty
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
        ->errorJson(400,'','Invalid HTTP body');
      return null;
    }

    # Fetch the Article
    $item = (new \App\Db\BlogArticleDao())->fetchBy('uuid',$body['uuid']);

    # Fetch the Blog (referenced by article.blog_id)
    $blog = (new \App\Db\BlogBlogDao())->fetchByKeywords(['blog_id'=>$item->getBlogId(),'account_id'=>$this->account->getId()]);
    if ( !$blog ) {
      response()
        ->errorJson(404,'','Parameter {uuis} is invalid');

      return true;
    }

    # Validate Title is not empty
    if ( empty($body['title']) ) {
      response()
        ->errorJson(404,'','Parameter {title} can not be empty');

      return true;
    }

    # Set fields and store the Article
    $item->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    if (!empty($body['title'])) $item->setTitle($body['title']);
    if (!empty($body['body'])) $item->setBody($body['body']);
    $ok = (new \App\Db\BlogArticleDao())->update($item);

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
   *   Model fields used:
   *     uuid
   *
   *   Checks performed:
   *   1.  Validate uuid is not empty/exists
   *   2.  Validate articles parent blog belongs to account
   *
   * @param  array  $args    Path variables as key=value array
   */
  public function handleDELETE(array $args)
  {
    $parUuid = $args['uuid'];

    # Fetch the Article
    $article = (new \App\Db\BlogArticleDao())->fetchBy('uuid',$parUuid);

    # Validate article exists
    if (!$article) {
      response()
        ->errorJson(400,'','Invalid {uuid}');

      return true;
    }

    # Fetch the Blog (referenced by article.blog_id)
    $blog = (new \App\Db\BlogBlogDao())->fetchBy('id',$article->getBlogId());
    if ( !$blog ) {
      response()
        ->errorJson(404,'','Invalid {uuid}');

      return true;
    }

    # Check the article belongs to the Account
    if ( $blog->getAccountId() != $this->account->getId() ) {
      response()
        ->errorJson(400,'','Invalid {uuid}');

      return true;
    }

    if ($article) {
      $ok = (new \App\Db\BlogArticleDao())->delete($article);
    }

    response()
      ->setStatusCode( 204 )
      ->setBody('');

    return true;
  }

}


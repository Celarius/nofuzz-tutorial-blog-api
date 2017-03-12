<?php declare(strict_types=1);

namespace Nofuzz;

class EntityTest extends \PHPUnit\Framework\TestCase
{
  protected $app;

  /** Setup test */
  public function setup()
  {
    $this->app = new \Nofuzz\Application( realpath(__DIR__) );
  }

  public function testAccountEntity()
  {
    $entity = new \App\Db\BlogAccount();

    $this->assertTrue(!is_null($entity));
  }

  public function testBlogEntity()
  {
    $entity = new \App\Db\BlogBlog();

    $this->assertTrue(!is_null($entity));
  }

  public function testArticleEntity()
  {
    $entity = new \App\Db\BlogArticle();

    $this->assertTrue(!is_null($entity));
  }

  public function testCommentEntity()
  {
    $entity = new \App\Db\BlogComment();

    $this->assertTrue(!is_null($entity));
  }

  public function testTokenEntity()
  {
    $entity = new \App\Db\BlogComment();

    $this->assertTrue(!is_null($entity));
  }
}

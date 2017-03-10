<?php 
/** 
 * BlogArticle.php
 *
 *    Entity for table blog_articles
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
  "blog_id": 0,
  "title": "",
  "body": "",
  "status": 0
}
*/
#########################################################################################

Use \App\Db\AbstractBaseEntity as AbstractBaseEntity;

namespace App\Db;

/** 
 * Class representing rows in table "blog_articles"
 * 
 * @uses     \App\Db\AbstractBaseEntity
 */
class BlogArticle extends AbstractBaseEntity
{
  protected $id;                                // id BigInt(20) NOT NULL AUTO_INCREMENT
  protected $created_dt;                        // created_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  protected $modified_dt;                       // modified_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  protected $uuid;                              // uuid NVarChar(64) COLLATE utf8_general_ci
  protected $blog_id;                           // blog_id BigInt(20) NOT NULL
  protected $title;                             // title NVarChar(128) COLLATE utf8_general_ci
  protected $body;                              // body MediumText CHARACTER SET utf8 COLLATE utf8_general_ci
  protected $status;                            // status SmallInt(6) DEFAULT 0

  /**
   * Clear properties to default values
   *
   * @return   self
   */
  public function clear()
  {
    $this->setId(0);
    $this->setCreatedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $this->setModifiedDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $this->setUuid('');
    $this->setBlogId(0);
    $this->setTitle('');
    $this->setBody('');
    $this->setStatus(0);

    return $this;
  }

  /**
   * Return object as array
   *
   * @return   array
   */
  public function asArray(): array
  {
    $result['id'] = $this->getId();
    $result['created_dt'] = $this->getCreatedDt();
    $result['modified_dt'] = $this->getModifiedDt();
    $result['uuid'] = $this->getUuid();
    $result['blog_id'] = $this->getBlogId();
    $result['title'] = $this->getTitle();
    $result['body'] = $this->getBody();
    $result['status'] = $this->getStatus();

    return $result;
  }

  /**
   * Set properties from array
   *
   * @return   self
   */
  public function fromArray(array $a)
  {
    $this->setId($a['id'] ?? 0);
    $this->setCreatedDt($a['created_dt'] ?? (new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $this->setModifiedDt($a['modified_dt'] ?? (new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
    $this->setUuid($a['uuid'] ?? '');
    $this->setBlogId($a['blog_id'] ?? 0);
    $this->setTitle($a['title'] ?? '');
    $this->setBody($a['body'] ?? '');
    $this->setStatus($a['status'] ?? 0);

    return $this;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = (int) $id;

    return $this;
  }

  public function getCreatedDt()
  {
    return $this->created_dt;
  }

  public function setCreatedDt($created_dt)
  {
    $this->created_dt = (new \DateTime($created_dt,new \DateTimeZone("UTC")))->format("Y-m-d H:i:s");

    return $this;
  }

  public function getModifiedDt()
  {
    return $this->modified_dt;
  }

  public function setModifiedDt($modified_dt)
  {
    $this->modified_dt = (new \DateTime($modified_dt,new \DateTimeZone("UTC")))->format("Y-m-d H:i:s");

    return $this;
  }

  public function getUuid()
  {
    return $this->uuid;
  }

  public function setUuid($uuid)
  {
    $this->uuid = $uuid;

    return $this;
  }

  public function getBlogId()
  {
    return $this->blog_id;
  }

  public function setBlogId($blog_id)
  {
    $this->blog_id = (int) $blog_id;

    return $this;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle($title)
  {
    $this->title = $title;

    return $this;
  }

  public function getBody()
  {
    return $this->body;
  }

  public function setBody($body)
  {
    $this->body = $body;

    return $this;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function setStatus($status)
  {
    $this->status = (int) $status;

    return $this;
  }

} // EOC


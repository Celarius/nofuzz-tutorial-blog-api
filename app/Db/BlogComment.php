<?php 
/** 
 * BlogComment.php
 *
 *    Entity for table blog_comments
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

namespace App\Db;

/** 
 * Class representing rows in table "blog_comments"
 */
class BlogComment extends \App\Db\AbstractBaseEntity
{
  protected $id;                                // id BigInt(20) NOT NULL AUTO_INCREMENT
  protected $created_dt;                        // created_dt DateTime
  protected $modified_dt;                       // modified_dt DateTime
  protected $uuid;                              // uuid NVarChar(64) COLLATE utf8_general_ci
  protected $article_id;                        // article_id BigInt(20) NOT NULL
  protected $account_id;                        // account_id BigInt(20) NOT NULL
  protected $comment;                           // `comment` Text CHARACTER SET utf8 COLLATE utf8_general_ci
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
    $this->setArticleId(0);
    $this->setAccountId(0);
    $this->setComment('');
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
    $result['article_id'] = $this->getArticleId();
    $result['account_id'] = $this->getAccountId();
    $result['comment'] = $this->getComment();
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
    $this->setArticleId($a['article_id'] ?? 0);
    $this->setAccountId($a['account_id'] ?? 0);
    $this->setComment($a['comment'] ?? '');
    $this->setStatus($a['status'] ?? 0);

    return $this;
  }

  public function getId()
  {
    if (!is_null($this->id)) return (int) $this->id;

    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

  public function getCreatedDt()
  {

    return $this->created_dt;
  }

  public function setCreatedDt($created_dt)
  {
    if (strcasecmp($created_dt,'0000-00-00 00:00:00')==0) $created_dt = null;

    $this->created_dt = $created_dt;

    if (!is_null($created_dt))
      $this->created_dt = (new \DateTime($created_dt,new \DateTimeZone("UTC")))->format("Y-m-d H:i:s");

    return $this;
  }

  public function getModifiedDt()
  {

    return $this->modified_dt;
  }

  public function setModifiedDt($modified_dt)
  {
    if (strcasecmp($modified_dt,'0000-00-00 00:00:00')==0) $modified_dt = null;

    $this->modified_dt = $modified_dt;

    if (!is_null($modified_dt))
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

  public function getArticleId()
  {
    if (!is_null($this->article_id)) return (int) $this->article_id;

    return $this->article_id;
  }

  public function setArticleId($article_id)
  {
    $this->article_id = $article_id;

    return $this;
  }

  public function getAccountId()
  {
    if (!is_null($this->account_id)) return (int) $this->account_id;

    return $this->account_id;
  }

  public function setAccountId($account_id)
  {
    $this->account_id = $account_id;

    return $this;
  }

  public function getComment()
  {

    return $this->comment;
  }

  public function setComment($comment)
  {
    $this->comment = $comment;

    return $this;
  }

  public function getStatus()
  {
    if (!is_null($this->status)) return (int) $this->status;

    return $this->status;
  }

  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }

} // EOC


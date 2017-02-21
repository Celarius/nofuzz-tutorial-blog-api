<?php
/**
 * articles.php
 *
 * @package  Nofuzz Appliction
 */
#########################################################################################

namespace App\Db;

/**
 * Class representing rows in table 'articles'
 */
class Article extends \Nofuzz\Database\BaseDbObject
{
  protected $id = '';                              // id BigInt(20) NOT NULL AUTO_INCREMENT
  protected $created_dt = null;                    // created_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  protected $modified_dt = null;                   // modified_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  protected $uuid = '';                            // uuid NVarChar(64)
  protected $blog_id = '';                         // blog_id BigInt(20) NOT NULL
  protected $title = '';                           // title NVarChar(128) COLLATE utf8_general_ci
  protected $body = '';                            // body MediumText CHARACTER SET utf8 COLLATE utf8_general_ci
  protected $status = null;                        // status SmallInt(6) DEFAULT 0

  public function clear()
  {
    $this->setId('');
    $this->setCreatedDt(null);
    $this->setModifiedDt(null);
    $this->setUuid('');
    $this->setBlogId('');
    $this->setTitle('');
    $this->setBody('');
    $this->setStatus(null);

    return $this;
  }

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

  public function fromArray(array $a)
  {
    $this->setId($a['id'] ?? '');
    $this->setCreatedDt($a['created_dt'] ?? null);
    $this->setModifiedDt($a['modified_dt'] ?? null);
    $this->setUuid($a['uuid'] ?? '');
    $this->setBlogId($a['blog_id'] ?? '');
    $this->setTitle($a['title'] ?? '');
    $this->setBody($a['body'] ?? '');
    $this->setStatus($a['status'] ?? null);

    return $this;
  }

  /**
   * Get Id
   * @return
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set Id
   * @param    $id
   */
  public function setId($id)
  {
    $this->id = $id;
    return $this;
  }

  /**
   * Get CreatedDt
   * @return string
   */
  public function getCreatedDt()
  {
    return $this->created_dt;
  }

  /**
   * Set CreatedDt
   * @param   string $created_dt
   */
  public function setCreatedDt($created_dt)
  {
    $this->created_dt = (new \DateTime($created_dt,new \DateTimeZone("UTC")))->format("Y-m-d H:i:s");
    return $this;
  }

  /**
   * Get ModifiedDt
   * @return string
   */
  public function getModifiedDt()
  {
    return $this->modified_dt;
  }

  /**
   * Set ModifiedDt
   * @param   string $modified_dt
   */
  public function setModifiedDt($modified_dt)
  {
    $this->modified_dt = (new \DateTime($modified_dt,new \DateTimeZone("UTC")))->format("Y-m-d H:i:s");
    return $this;
  }

  /**
   * Get Uuid
   * @return string
   */
  public function getUuid()
  {
    return $this->uuid;
  }

  /**
   * Set Uuid
   * @param   string $uuid
   */
  public function setUuid($uuid)
  {
    $this->uuid = $uuid;
    return $this;
  }

  /**
   * Get BlogId
   * @return
   */
  public function getBlogId()
  {
    return $this->blog_id;
  }

  /**
   * Set BlogId
   * @param    $blog_id
   */
  public function setBlogId($blog_id)
  {
    $this->blog_id = $blog_id;
    return $this;
  }

  /**
   * Get Title
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set Title
   * @param   string $title
   */
  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }

  /**
   * Get Body
   * @return string
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * Set Body
   * @param   string $body
   */
  public function setBody($body)
  {
    $this->body = $body;
    return $this;
  }

  /**
   * Get Status
   * @return int
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * Set Status
   * @param   int $status
   */
  public function setStatus($status)
  {
    $this->status = $status;
    return $this;
  }

} // EOC


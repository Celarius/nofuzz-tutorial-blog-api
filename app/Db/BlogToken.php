<?php 
/** 
 * BlogToken.php
 *
 *    Entity for table blog_tokens
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
  "sessionid": "",
  "account_id": 0,
  "expires_dt": "1970-01-01 00:00:00",
  "status": 0
}
*/
#########################################################################################

Use \App\Db\AbstractBaseEntity as AbstractBaseEntity;

namespace App\Db;

/** 
 * Class representing rows in table "blog_tokens"
 * 
 * @uses     \App\Db\AbstractBaseEntity
 */
class BlogToken extends AbstractBaseEntity
{
  protected $id;                                // id BigInt(20) NOT NULL AUTO_INCREMENT
  protected $created_dt;                        // created_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  protected $modified_dt;                       // modified_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  protected $sessionid;                         // sessionid NVarChar(64) COLLATE utf8_general_ci
  protected $account_id;                        // account_id BigInt(20) NOT NULL
  protected $expires_dt;                        // expires_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
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
    $this->setSessionid('');
    $this->setAccountId(0);
    $this->setExpiresDt((new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
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
    $result['sessionid'] = $this->getSessionid();
    $result['account_id'] = $this->getAccountId();
    $result['expires_dt'] = $this->getExpiresDt();
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
    $this->setSessionid($a['sessionid'] ?? '');
    $this->setAccountId($a['account_id'] ?? 0);
    $this->setExpiresDt($a['expires_dt'] ?? (new \DateTime("now",new \DateTimeZone("UTC")))->format("Y-m-d H:i:s"));
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

  public function getSessionid()
  {
    return $this->sessionid;
  }

  public function setSessionid($sessionid)
  {
    $this->sessionid = $sessionid;

    return $this;
  }

  public function getAccountId()
  {
    return $this->account_id;
  }

  public function setAccountId($account_id)
  {
    $this->account_id = (int) $account_id;

    return $this;
  }

  public function getExpiresDt()
  {
    return $this->expires_dt;
  }

  public function setExpiresDt($expires_dt)
  {
    $this->expires_dt = (new \DateTime($expires_dt,new \DateTimeZone("UTC")))->format("Y-m-d H:i:s");

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


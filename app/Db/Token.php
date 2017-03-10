<?php
/**
 * Token.php
 *
 * @since    2017-03-07 20:52:50
 * @package  Nofuzz Appliction
 */
#########################################################################################
/*
JSON Model:
{
  "id": 0,
  "created_dt": "",
  "modified_dt": "",
  "sessionid": "string",
  "account_id": 0,
  "expires_dt": "",
  "status": 0
}
*/
#########################################################################################

namespace App\Db;

/**
 * Class representing rows in table 'blog_tokens'
 */
class Token extends \App\Db\AbstractBaseEntity
{
  protected $id;                                   // id BigInt(20) NOT NULL AUTO_INCREMENT
  protected $created_dt;                           // created_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  protected $modified_dt;                          // modified_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  protected $sessionid;                            // sessionid NVarChar(64) COLLATE utf8_general_ci
  protected $account_id;                           // account_id BigInt(20) NOT NULL
  protected $expires_dt;                           // expires_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
  protected $status;                               // status SmallInt(6) DEFAULT 0

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

  /**
   * Get Id
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set Id
   * @param   int $id
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
   * Get Sessionid
   * @return string
   */
  public function getSessionid()
  {
    return $this->sessionid;
  }

  /**
   * Set Sessionid
   * @param   string $sessionid
   */
  public function setSessionid($sessionid)
  {
    $this->sessionid = $sessionid;
    return $this;
  }

  /**
   * Get AccountId
   * @return int
   */
  public function getAccountId()
  {
    return $this->account_id;
  }

  /**
   * Set AccountId
   * @param   int $account_id
   */
  public function setAccountId($account_id)
  {
    $this->account_id = $account_id;
    return $this;
  }

  /**
   * Get ExpiresDt
   * @return string
   */
  public function getExpiresDt()
  {
    return $this->expires_dt;
  }

  /**
   * Set ExpiresDt
   * @param   string $expires_dt
   */
  public function setExpiresDt($expires_dt)
  {
    $this->expires_dt = (new \DateTime($expires_dt,new \DateTimeZone("UTC")))->format("Y-m-d H:i:s");
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

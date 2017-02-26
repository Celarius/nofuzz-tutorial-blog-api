<?php
/**
 * tokens.php
 *
 * @package  Nofuzz Appliction
 */
#########################################################################################

namespace App\Db;

class Token extends \App\Db\AbstractBaseDbObject
{
  protected $id = null;                            // id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY
  protected $created_dt = null;                    // created_dt TIMESTAMP
  protected $modified_dt = null;                   // modified_dt TIMESTAMP
  protected $sessionid = '';                       // sessionid NVarChar(64) COLLATE utf8_general_ci
  protected $account_id = null;                    // account_id BIGINT NOT NULL
  protected $expires_dt = null;                    // expires_dt TIMESTAMP
  protected $status = null;                        // status SMALLINT DEFAULT 0

  public function clear()
  {
    $this->setId(null);
    $this->setCreatedDt(null);
    $this->setModifiedDt(null);
    $this->setAccountId(null);
    $this->setSessionid('');
    $this->setExpiresDt(null);
    $this->setStatus(null);

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
    $this->setId($a['id'] ?? null);
    $this->setCreatedDt($a['created_dt'] ?? null);
    $this->setModifiedDt($a['modified_dt'] ?? null);
    $this->setSessionid($a['sessionid'] ?? '');
    $this->setAccountId($a['account_id'] ?? null);
    $this->setExpiresDt($a['expires_dt'] ?? null);
    $this->setStatus($a['status'] ?? null);

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

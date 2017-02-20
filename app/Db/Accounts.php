<?php
/**
 * accounts.php
 *
 * @package  Nofuzz Appliction
 */
#########################################################################################

namespace App\Db\Accounts;

/**
 * Class representing rows in table 'accounts'
 */
class Accounts extends \Nofuzz\Database\BaseDbObject
{
  protected $id = null;                            // id BIGINT NOT NULL AUTO_INCREMENT
  protected $created_dt = null;                    // created_dt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
  protected $modified_dt = null;                   // modified_dt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  protected $uuid = '';                            // uuid NVarChar(32) COLLATE utf8_general_ci
  protected $login_name = '';                      // login_name NVarChar(32) COLLATE utf8_general_ci
  protected $first_name = '';                      // first_name NVarChar(32) COLLATE utf8_general_ci
  protected $last_name = '';                       // last_name NVarChar(32) COLLATE utf8_general_ci
  protected $jwt_secret = '';                      // jwt_secret NVarChar(64) COLLATE utf8_general_ci
  protected $pw_salt = '';                         // pw_salt NVarChar(128) COLLATE utf8_general_ci
  protected $pw_hash = '';                         // pw_hash NVarChar(128) COLLATE utf8_general_ci
  protected $pw_iterations = null;                 // pw_iterations INT DEFAULT 1
  protected $status = null;                        // status SMALLINT DEFAULT 0

  public function clear()
  {
    $this->setId(null);
    $this->setCreatedDt(null);
    $this->setModifiedDt(null);
    $this->setUuid('');
    $this->setLoginName('');
    $this->setFirstName('');
    $this->setLastName('');
    $this->setJwtSecret('');
    $this->setPwSalt('');
    $this->setPwHash('');
    $this->setPwIterations(null);
    $this->setStatus(null);

    return $this;
  }

  public function asArray(): array
  {
    $result['id'] = $this->getId();
    $result['created_dt'] = $this->getCreatedDt();
    $result['modified_dt'] = $this->getModifiedDt();
    $result['uuid'] = $this->getUuid();
    $result['login_name'] = $this->getLoginName();
    $result['first_name'] = $this->getFirstName();
    $result['last_name'] = $this->getLastName();
    $result['jwt_secret'] = $this->getJwtSecret();
    $result['pw_salt'] = $this->getPwSalt();
    $result['pw_hash'] = $this->getPwHash();
    $result['pw_iterations'] = $this->getPwIterations();
    $result['status'] = $this->getStatus();

    return $result;
  }

  public function fromArray(array $a)
  {
    $this->setId($a['id'] ?? null);
    $this->setCreatedDt($a['created_dt'] ?? null);
    $this->setModifiedDt($a['modified_dt'] ?? null);
    $this->setUuid($a['uuid'] ?? '');
    $this->setLoginName($a['login_name'] ?? '');
    $this->setFirstName($a['first_name'] ?? '');
    $this->setLastName($a['last_name'] ?? '');
    $this->setJwtSecret($a['jwt_secret'] ?? '');
    $this->setPwSalt($a['pw_salt'] ?? '');
    $this->setPwHash($a['pw_hash'] ?? '');
    $this->setPwIterations($a['pw_iterations'] ?? null);
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
   * Get LoginName
   * @return string
   */
  public function getLoginName()
  {
    return $this->login_name;
  }

  /**
   * Set LoginName
   * @param   string $login_name
   */
  public function setLoginName($login_name)
  {
    $this->login_name = $login_name;
    return $this;
  }

  /**
   * Get FirstName
   * @return string
   */
  public function getFirstName()
  {
    return $this->first_name;
  }

  /**
   * Set FirstName
   * @param   string $first_name
   */
  public function setFirstName($first_name)
  {
    $this->first_name = $first_name;
    return $this;
  }

  /**
   * Get LastName
   * @return string
   */
  public function getLastName()
  {
    return $this->last_name;
  }

  /**
   * Set LastName
   * @param   string $last_name
   */
  public function setLastName($last_name)
  {
    $this->last_name = $last_name;
    return $this;
  }

  /**
   * Get JwtSecret
   * @return string
   */
  public function getJwtSecret()
  {
    return $this->jwt_secret;
  }

  /**
   * Set JwtSecret
   * @param   string $jwt_secret
   */
  public function setJwtSecret($jwt_secret)
  {
    $this->jwt_secret = $jwt_secret;
    return $this;
  }

  /**
   * Get PwSalt
   * @return string
   */
  public function getPwSalt()
  {
    return $this->pw_salt;
  }

  /**
   * Set PwSalt
   * @param   string $pw_salt
   */
  public function setPwSalt($pw_salt)
  {
    $this->pw_salt = $pw_salt;
    return $this;
  }

  /**
   * Get PwHash
   * @return string
   */
  public function getPwHash()
  {
    return $this->pw_hash;
  }

  /**
   * Set PwHash
   * @param   string $pw_hash
   */
  public function setPwHash($pw_hash)
  {
    $this->pw_hash = $pw_hash;
    return $this;
  }

  /**
   * Get PwIterations
   * @return int
   */
  public function getPwIterations()
  {
    return $this->pw_iterations;
  }

  /**
   * Set PwIterations
   * @param   int $pw_iterations
   */
  public function setPwIterations($pw_iterations)
  {
    $this->pw_iterations = $pw_iterations;
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

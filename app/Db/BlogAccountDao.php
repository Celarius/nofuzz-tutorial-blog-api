<?php
/**
 * BlogAccountDao.php
 *
 *    Dao class for table blog_accounts
 *
 *  Generated with DaoGen v0.4.8
 *
 * @since    2017-03-18 21:42:54
 * @package  App\Db
 */
#########################################################################################

Use App\Db\AbstractBaseEntity as AbstractBaseEntity;

namespace App\Db;

/**
 * Dao class for rows in table "blog_accounts"
 */
class BlogAccountDao extends \App\Db\AbstractBaseDao
{
  /**
   * Constructor
   *
   * @param string  $connectionname    Database ConnectionName
   */
  public function __construct(string $connectionName='')
  {
    parent::__construct($connectionName);
    $this->setTable('blog_accounts');
    $this->setCacheTTL(60);
  }

  /**
   * Make/Generate an Entity
   *
   * @param  array  $fields             Array with key=value for fields
   * @return object
   */
  function makeEntity(array $fields=[]): AbstractBaseEntity
  {
    $item = new \App\Db\BlogAccount(array_change_key_case($fields),CASE_LOWER);
    $this->cacheSetItem($item);

    return $item;
  }

  /**
   * Fetch all records in table
   *
   * @return array
   */
  public function fetchAll(): array
  {
    if ($items = $this->cacheGetAll()) return $items;

    $items =
      $this->fetchCustom(
        'SELECT * FROM {table}'
      );

    if ($items) $this->cacheSetAll($items);

    return $items;
  }

  /**
   * Fetch records by Keywords
   *
   * @param  array $keywords            Array with keyword = value
   * @return array
   */
  public function fetchByKeywords(array $keywords=[]): array
  {
    $where = '';
    $order = '';
    $limit = '';
    $binds = [];

    if (isset($keywords['id']) && strlen($keywords['id'])>0) {
      $where .= 'AND (id = :ID) ';
      $binds[':ID'] = $keywords['id'];
    }

    if (isset($keywords['created_dt']) && strlen($keywords['created_dt'])>0) {
      $where .= 'AND (created_dt = :CREATED_DT) ';
      $binds[':CREATED_DT'] = $keywords['created_dt'];
    }

    if (isset($keywords['modified_dt']) && strlen($keywords['modified_dt'])>0) {
      $where .= 'AND (modified_dt = :MODIFIED_DT) ';
      $binds[':MODIFIED_DT'] = $keywords['modified_dt'];
    }

    if (isset($keywords['uuid']) && strlen($keywords['uuid'])>0) {
      $where .= 'AND (uuid LIKE :UUID) ';
      $binds[':UUID'] = $keywords['uuid'];
    }

    if (isset($keywords['login_name']) && strlen($keywords['login_name'])>0) {
      $where .= 'AND (login_name LIKE :LOGIN_NAME) ';
      $binds[':LOGIN_NAME'] = $keywords['login_name'];
    }

    if (isset($keywords['first_name']) && strlen($keywords['first_name'])>0) {
      $where .= 'AND (first_name LIKE :FIRST_NAME) ';
      $binds[':FIRST_NAME'] = $keywords['first_name'];
    }

    if (isset($keywords['last_name']) && strlen($keywords['last_name'])>0) {
      $where .= 'AND (last_name LIKE :LAST_NAME) ';
      $binds[':LAST_NAME'] = $keywords['last_name'];
    }

    if (isset($keywords['email']) && strlen($keywords['email'])>0) {
      $where .= 'AND (email LIKE :EMAIL) ';
      $binds[':EMAIL'] = $keywords['email'];
    }

    if (isset($keywords['jwt_secret']) && strlen($keywords['jwt_secret'])>0) {
      $where .= 'AND (jwt_secret LIKE :JWT_SECRET) ';
      $binds[':JWT_SECRET'] = $keywords['jwt_secret'];
    }

    if (isset($keywords['pw_salt']) && strlen($keywords['pw_salt'])>0) {
      $where .= 'AND (pw_salt LIKE :PW_SALT) ';
      $binds[':PW_SALT'] = $keywords['pw_salt'];
    }

    if (isset($keywords['pw_hash']) && strlen($keywords['pw_hash'])>0) {
      $where .= 'AND (pw_hash LIKE :PW_HASH) ';
      $binds[':PW_HASH'] = $keywords['pw_hash'];
    }

    if (isset($keywords['pw_iterations']) && strlen($keywords['pw_iterations'])>0) {
      $where .= 'AND (pw_iterations = :PW_ITERATIONS) ';
      $binds[':PW_ITERATIONS'] = $keywords['pw_iterations'];
    }

    if (isset($keywords['status']) && strlen($keywords['status'])>0) {
      $where .= 'AND (status = :STATUS) ';
      $binds[':STATUS'] = $keywords['status'];
    }

    if (!empty($where))
      $where = 'WHERE '.ltrim($where,'AND ');

    if (!empty($keywords['order'])) // Note here that we use the $keyword['order'] directly in SQL string.
      $order = ' ORDER BY '.$keywords['order'];

    if (!empty($keywords['limit'])) { // Note here that we use the $keyword['limit'] directly in SQL string.
      if (strcasecmp('mysql',$this->getConnection()->getDriver())==0) {
        $limit = ' LIMIT '.$keywords['limit'];
      } else
      if (strcasecmp('firebird',$this->getConnection()->getDriver())==0) {
        $limit = ' ROWS '.$keywords['limit'];
      }
    }

    return
      $this->fetchCustom(
        'SELECT * FROM {table} '.$where.$order.$limit,
        $binds
      );
  }

  /**
   * Insert $item into database
   *
   * @param  AbstractBaseEntity $item      The item we are inserting
   * @return bool
   */
  public function insert(AbstractBaseEntity &$item): bool
  {
    $id =
      $this->execCustomGetLastId(
        'INSERT INTO {table} '.
        '( id, created_dt, modified_dt, uuid, login_name, first_name, last_name, email, jwt_secret, pw_salt, pw_hash, pw_iterations, status) '.
        'VALUES '.
        '(:ID,:CREATED_DT,:MODIFIED_DT,:UUID,:LOGIN_NAME,:FIRST_NAME,:LAST_NAME,:EMAIL,:JWT_SECRET,:PW_SALT,:PW_HASH,:PW_ITERATIONS,:STATUS)',
        [
          ':ID' => $item->getId(),
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':UUID' => $item->getUuid(),
          ':LOGIN_NAME' => $item->getLoginName(),
          ':FIRST_NAME' => $item->getFirstName(),
          ':LAST_NAME' => $item->getLastName(),
          ':EMAIL' => $item->getEmail(),
          ':JWT_SECRET' => $item->getJwtSecret(),
          ':PW_SALT' => $item->getPwSalt(),
          ':PW_HASH' => $item->getPwHash(),
          ':PW_ITERATIONS' => $item->getPwIterations(),
          ':STATUS' => $item->getStatus()
        ]
      );

    $item->setId($id);

    $this->cacheSetItem($item);

    return ($id !=0);
  }

  /**
   * Update $item in database
   *
   * @param  AbstractBaseEntity $item      The item we are updating
   * @return bool
   */
  public function update(AbstractBaseEntity $item): bool
  {
    $ok =
      $this->execCustom(
        'UPDATE {table} SET '.
        ' created_dt = :CREATED_DT, '.
        ' modified_dt = :MODIFIED_DT, '.
        ' uuid = :UUID, '.
        ' login_name = :LOGIN_NAME, '.
        ' first_name = :FIRST_NAME, '.
        ' last_name = :LAST_NAME, '.
        ' email = :EMAIL, '.
        ' jwt_secret = :JWT_SECRET, '.
        ' pw_salt = :PW_SALT, '.
        ' pw_hash = :PW_HASH, '.
        ' pw_iterations = :PW_ITERATIONS, '.
        ' status = :STATUS '.
        'WHERE '.
        ' id = :ID ',
        [
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':UUID' => $item->getUuid(),
          ':LOGIN_NAME' => $item->getLoginName(),
          ':FIRST_NAME' => $item->getFirstName(),
          ':LAST_NAME' => $item->getLastName(),
          ':EMAIL' => $item->getEmail(),
          ':JWT_SECRET' => $item->getJwtSecret(),
          ':PW_SALT' => $item->getPwSalt(),
          ':PW_HASH' => $item->getPwHash(),
          ':PW_ITERATIONS' => $item->getPwIterations(),
          ':STATUS' => $item->getStatus(),
          ':ID' => $item->getId()
        ]
      );

    if ($ok) $this->cacheSetItem($item);

    return $ok;
  }

} // EOC


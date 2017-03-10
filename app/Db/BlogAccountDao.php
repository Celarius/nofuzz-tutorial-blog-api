<?php
/**
 * BlogAccountDao.php
 *
 *    Dao class for table blog_accounts
 *
 *  Generated with DaoGen v0.4.3
 *
 * @since    2017-03-10 19:24:29
 * @package  App\Db
 */
#########################################################################################

Use \App\Db\AbstractBaseDao as AbstractBaseDao;
Use \App\Db\AbstractBaseEntity as AbstractBaseEntity;

namespace App\Db;

/**
 * Dao class for rows in table "blog_accounts"
 *
 * @uses     \App\Db\AbstractBaseDao
 * @uses     \App\Db\AbstractBaseEntity
 */
class BlogAccountDao extends AbstractBaseDao
{
  /**
   * Constructor
   *
   * @param string  $connectionname    Database ConnectionName
   */
  public function __construct(string $connectionName)
  {
    parent::__construct($connectionName);
    $this->setTable('blog_accounts');
  }

  /**
   * Make/Generate an Entity
   *
   * @param  array  $fields             Array with key=value for fields
   * @return object
   */
  function makeEntity(array $fields=[]): AbstractBaseEntity
  {
    return new \App\Db\BlogAccount(array_change_key_case($fields),CASE_LOWER);
  }

  /**
   * Fetch all records in table
   *
   * @return array
   */
  public function fetchAll(): array
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table}'
      );
  }

  /**
   * Fetch record by Id
   *
   * @param  int $id                    The id
   * @return array|null
   */
  public function fetchById(int $id)
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE id = :ID',
        [':ID' => $id]
      )[0] ?? null;
  }

  /**
   * Fetch record by uuid
   *
   * @param  string $uuid               The uuid
   * @return array|null
   */
  public function fetchByUuid(string $uuid)
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE uuid = :UUID',
        [':UUID' => $uuid]
      )[0] ?? null;
  }

  /**
   * Get record by login_name
   *
   * @param  string $loginName    The Login Name
   * @return array
   */
  public function fetchByLoginName(string $loginName)
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE login_name = :LOGIN_NAME',
        [':LOGIN_NAME' => $loginName]
      )[0] ?? null;
  }

  /**
   * Get record by email_name
   *
   * @param  string $loginName    The Login Name
   * @return array
   */
  public function fetchByEmail(string $email)
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE email = :EMAIL',
        [':EMAIL' => $email]
      )[0] ?? null;
  }

  /**
   * Fetch records by Keyword
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

    if (!empty($keywords['id'])) {
       $where .= 'AND (id = :ID) ';
       $binds[':ID'] = $keywords['id'];
    }

    if (!empty($keywords['uuid'])) {
       $where .= 'AND (uuid = :UUID) ';
       $binds[':UUID'] = $keywords['uuid'];
    }

    if (!empty($keywords['name'])) {
      $where .= 'AND (login_name LIKE :Q1 or first_name LIKE :Q2 OR last_name LIKE :Q3 or email LIKE :Q4) ';
      $binds[':Q1'] = $keywords['q'];
      $binds[':Q2'] = $keywords['q'];
      $binds[':Q3'] = $keywords['q'];
      $binds[':Q4'] = $keywords['q'];
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
   * @param  \App\Db\AbstractBaseEntity $item      The item we are inserting
   * @return bool
   */
  public function insert(\App\Db\AbstractBaseEntity &$item): bool
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

    return ($id !=0);
  }

  /**
   * Update $item in database
   *
   * @param  \App\Db\AbstractBaseEntity $item      The item we are updating
   * @return bool
   */
  public function update(\App\Db\AbstractBaseEntity $item): bool
  {
    return
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
  }

} // EOC


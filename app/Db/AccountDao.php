<?php
/**
 * AccountDao
 *
 * @package     Nofuzz-blog-tutorial
*/
################################################################################################################################

namespace App\Db;

class AccountDao extends \App\Db\AbstractBaseDao
{
  /**
   * Constructor
   */
  public function __construct(string $connectionName)
  {
    parent::__construct($connectionName);
    $this->setTable('blog_accounts');
  }

  /**
   * Make/Generate an Entity
   *
   * @param  array  $fields [description]
   * @return object
   */
  function makeEntity(array $fields=[]): \App\Db\AbstractBaseEntity
  {
    return new \App\Db\Account(array_change_key_case($fields),CASE_LOWER);
  }

  /**
   * Fetch all records in table
   *
   * @return array
   */
  public function fetchAll(): array
  {
    return $this->fetchCustom(
              'SELECT * FROM {table}'
            );
  }

  /**
   * Get record by Id
   *
   * @param  int $id              The id
   * @return null|object
   */
  public function fetchById(int $id): array
  {
    return $this->fetchCustom(
              'SELECT * FROM {table} WHERE id = :ID ',
              [':ID' => $id]
            );
  }

  /**
   * Get record by UUID
   *
   * @param  string $UUID              The UUID
   * @return null|object
   */
  public function fetchByUuid(string $uuid): array
  {
    return $this->fetchCustom(
              'SELECT * FROM {table} WHERE uuid = :UUID ',
              [':UUID' => $uuid]
            );
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
      );
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
      );
  }

  /**
   * Get records by keywords
   *
   * @param  array $keywords
   * @return array
   */
  public function fetchByKeywords(array $keywords=[])
  {
    $where = '';
    $order = '';
    $binds = [];

    if (!empty($keywords['id'])) {
      $where .= 'AND (id = :ID) ';
      $binds[':ID'] = $keywords['id'];
    }

    if (!empty($keywords['name'])) {
      $where .= 'AND (login_name LIKE :Q1 or first_name LIKE :Q2 OR last_name LIKE :Q3 or email LIKE :Q4) ';
      $binds[':Q1'] = $keywords['q'];
      $binds[':Q2'] = $keywords['q'];
      $binds[':Q3'] = $keywords['q'];
      $binds[':Q4'] = $keywords['q'];
    }

    if (!empty($where)) {
      $where = 'WHERE '.ltrim($where,'AND ');
    }

    if (!empty($keywords['order'])) {
      // Note here that we use the $keyword['order'] directly in SQL string.
      // so MAKE SURE you have control over what comes in here ...
      $order = ' ORDER BY '.$keywords['order'];
    }

    return
      $this->fetchCustom(
        'SELECT * FROM {table} '.$where.$order,
        $binds
      );
  }


  /**
   * Insert
   *
   * @param  \App\Db\Account $item      [description]
   * @return bool                       True=Success, False=Failed
   */
  public function insert(\App\Db\AbstractBaseEntity &$item): bool
  {
    $id =
      $this->execCustomGetLastId(
        'INSERT INTO {table} '.
        ' ( uuid, login_name, first_name, last_name, email, jwt_secret, pw_salt, pw_hash, pw_iterations, status) '.
        'VALUES '.
        ' (:UUID,:LOGIN_NAME,:FIRST_NAME,:LAST_NAME,:EMAIL,:JWT_SECRET,:PW_SALT,:PW_HASH,:PW_ITERATIONS,:STATUS)',
        [
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
   * Update
   *
   * @param  \App\Db\Account $item      [description]
   * @return bool                       True=Success, False=Failed
   */
  public function update(\App\Db\AbstractBaseEntity $item): bool
  {
    return
      $this->execCustom(
        'UPDATE {table} SET '.
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
        ' id = :ID',
        [
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

}

<?php
/**
 * AccountDao
 *
 * @package     Nofuzz
*/
################################################################################################################################

namespace App\Db;

class AccountDao extends \App\Db\AbstractBaseDao
{
  /**
   * Constructor
   *
   * @param \Nofuzz\Database\PdoConnectionInterface $connection
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
  function makeEntity(array $fields=[]): \App\Db\AbstractBaseDbObject
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
   * Cache result 'accounts:id:<id>'
   *
   * @param  int $id              The id
   * @return null|object
   */
  public function fetchById(int $id): array
  {
    return $this->fetchCustom(
              'SELECT * FROM {table} WHERE id = :ID ',
              [':ID' => $item->getId()]
            );
  }

  /**
   * Get record by login_name
   *
   * Cache result 'accounts:id:<id>', 'accounts:loginName:<name>'
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
   * Cache result 'accounts:email:<id>'
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
   * Insert
   *
   * @param  \App\Db\Account $account [description]
   * @return bool                     True=Success, False=Failed
   */
  public function insert(\App\Db\AbstractBaseDbObject $account): bool
  {
    $id =
      $this->execCustom(
        'INSERT INTO {table} '.
        ' ( uuid, login_name, first_name, last_name, email, jwt_secret, pw_salt, pw_hash, pw_iterations, status) '.
        'VALUES '.
        ' (:UUID,:LOGIN_NAME,:FIRST_NAME,:LAST_NAME,:EMAIL,:JWT_SECRET,:PW_SALT,:PW_HASH,:PW_ITERATIONS,:STATUS)',
        [
          ':UUID' => $account->getUuid(),
          ':LOGIN_NAME' => $account->getLoginName(),
          ':FIRST_NAME' => $account->getFirstName(),
          ':LAST_NAME' => $account->getLastName(),
          ':EMAIL' => $account->getEmail(),
          ':JWT_SECRET' => $account->getJwtSecret(),
          ':PW_SALT' => $account->getPwSalt(),
          ':PW_HASH' => $account->getPwHash(),
          ':PW_ITERATIONS' => $account->getPwIterations(),
          ':STATUS' => $account->getStatus()
        ]
      );

    if ($id > 0) {
      $account->setId($id);

      return true;
    }

    return false;
  }


  /**
   * Update
   *
   * @param  \App\Db\Account $account [description]
   * @return bool                     True=Success, False=Failed
   */
  public function update(\App\Db\AbstractBaseDbObject $item): bool
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
          ':UUID' => $account->getUuid(),
          ':LOGIN_NAME' => $account->getLoginName(),
          ':FIRST_NAME' => $account->getFirstName(),
          ':LAST_NAME' => $account->getLastName(),
          ':EMAIL' => $account->getEmail(),
          ':JWT_SECRET' => $account->getJwtSecret(),
          ':PW_SALT' => $account->getPwSalt(),
          ':PW_HASH' => $account->getPwHash(),
          ':PW_ITERATIONS' => $account->getPwIterations(),
          ':STATUS' => $account->getStatus(),
          ':ID' => $account->getId()
        ]
      );
  }

}

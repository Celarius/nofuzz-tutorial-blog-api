<?php
/**
 * ArticleDao
 *
 * @package     Nofuzz-blog-tutorial
*/
################################################################################################################################

namespace App\Db;

class ArticleDao extends \App\Db\AbstractBaseDao
{
  /**
   * Constructor
   *
   * @param \Nofuzz\Database\PdoConnectionInterface $connection
   */
  public function __construct(string $connectionName)
  {
    parent::__construct($connectionName);
    $this->setTable('blog_articles');
  }

  /**
   * Make/Generate an Entity
   *
   * @param  array  $fields [description]
   * @return object
   */
  function makeEntity(array $fields=[]): \App\Db\AbstractBaseEntity
  {
    return new \App\Db\Article(array_change_key_case($fields),CASE_LOWER);
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
   * @param  int $UUID              The UUID
   * @return null|object
   */
  public function fetchByUuid(int $uuid): array
  {
    return $this->fetchCustom(
              'SELECT * FROM {table} WHERE uuid = :UUID ',
              [':UUID' => $uuid]
            );
  }

  /**
   * Get record by title
   *
   * @param  string $title          The title
   * @return array
   */
  public function fetchByTitle(string $title)
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE LOWER(title) = :TITLE',
        [':TITLE' => strtolower($title)]
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
    $binds = [];

    if (!empty($keywords['title'])) {
      $where .= 'AND (title LIKE :TITLE) ';
      $binds[':TITLE'] = $title;
    }

    if (!empty($keywords['q'])) {
      $where .= 'AND (title LIKE :Q1 OR body LIKE :Q2) ';
      $binds[':Q1'] = $q;
      $binds[':Q2'] = $q;
    }

    if (!empty($where)) {
      $where = 'WHERE '.ltrim($where,'AND ');
    }

    return
      $this->fetchCustom(
        'SELECT * FROM {table} '.$where,
        $binds
      );
  }


  /**
   * Insert
   *
   * @param  \App\Db\Account $account [description]
   * @return bool                     True=Success, False=Failed
   */
  public function insert(\App\Db\AbstractBaseEntity &$account): bool
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

    $account->setId($id);

    return true;
  }


  /**
   * Update
   *
   * @param  \App\Db\Account $account [description]
   * @return bool                     True=Success, False=Failed
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

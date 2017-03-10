<?php
/**
 * BlogTokenDao.php
 *
 *    Dao class for table blog_tokens
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
 * Dao class for rows in table "blog_tokens"
 *
 * @uses     \App\Db\AbstractBaseDao
 * @uses     \App\Db\AbstractBaseEntity
 */
class BlogTokenDao extends AbstractBaseDao
{
  /**
   * Constructor
   *
   * @param string  $connectionname    Database ConnectionName
   */
  public function __construct(string $connectionName)
  {
    parent::__construct($connectionName);
    $this->setTable('blog_tokens');
  }

  /**
   * Make/Generate an Entity
   *
   * @param  array  $fields             Array with key=value for fields
   * @return object
   */
  function makeEntity(array $fields=[]): AbstractBaseEntity
  {
    return new \App\Db\BlogToken(array_change_key_case($fields),CASE_LOWER);
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
   * Fetch record by sessionid
   *
   * @param  string $sessionId          The sessionId
   * @return array|null
   */
  public function fetchBySessionId(string $sessionId)
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE sessionid = :SESSIONID',
        [':SESSIONID' => $sessionId]
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

    if (!empty($keywords['sessionid'])) {
       $where .= 'AND (sessionid = :SESSIONID) ';
       $binds[':SESSIONID'] = $keywords['sessionid'];
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
        '( id, created_dt, modified_dt, sessionid, account_id, expires_dt, status) '.
        'VALUES '.
        '(:ID,:CREATED_DT,:MODIFIED_DT,:SESSIONID,:ACCOUNT_ID,:EXPIRES_DT,:STATUS)',
        [
          ':ID' => $item->getId(),
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':SESSIONID' => $item->getSessionid(),
          ':ACCOUNT_ID' => $item->getAccountId(),
          ':EXPIRES_DT' => $item->getExpiresDt(),
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
        ' sessionid = :SESSIONID, '.
        ' account_id = :ACCOUNT_ID, '.
        ' expires_dt = :EXPIRES_DT, '.
        ' status = :STATUS '.
        'WHERE '.
        ' id = :ID ',
        [
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':SESSIONID' => $item->getSessionid(),
          ':ACCOUNT_ID' => $item->getAccountId(),
          ':EXPIRES_DT' => $item->getExpiresDt(),
          ':STATUS' => $item->getStatus(),
          ':ID' => $item->getId()
        ]
      );
  }

} // EOC


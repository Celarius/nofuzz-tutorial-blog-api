<?php
/**
 * TokenDao.php
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
class TokenDao extends \App\Db\AbstractBaseDao
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
  function makeEntity(array $fields=[]): \App\Db\AbstractBaseEntity
  {
    return new \App\Db\Token(array_change_key_case($fields),CASE_LOWER);
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
   * Fetch records by Keyword
   *
   * @param  array $keywords            Array with keyword = value
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

    if (!empty($where))
      $where = 'WHERE '.ltrim($where,'AND ');

    if (!empty($keywords['order'])) // Note here that we use the $keyword['order'] directly in SQL string.
      $order = ' ORDER BY '.$keywords['order'];

    return
      $this->fetchCustom(
        'SELECT * FROM {table} '.$where.$order,
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
        ' status = :STATUS'.
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

<?php
/**
 * BlogCommentDao.php
 *
 *    Dao class for table blog_comments
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
 * Dao class for rows in table "blog_comments"
 *
 * @uses     \App\Db\AbstractBaseDao
 * @uses     \App\Db\AbstractBaseEntity
 */
class BlogCommentDao extends AbstractBaseDao
{
  /**
   * Constructor
   *
   * @param string  $connectionname    Database ConnectionName
   */
  public function __construct(string $connectionName)
  {
    parent::__construct($connectionName);
    $this->setTable('blog_comments');
  }

  /**
   * Make/Generate an Entity
   *
   * @param  array  $fields             Array with key=value for fields
   * @return object
   */
  function makeEntity(array $fields=[]): AbstractBaseEntity
  {
    return new \App\Db\BlogComment(array_change_key_case($fields),CASE_LOWER);
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
   * Get records by article_id
   *
   * @param  string $article_id          The article_id
   * @return array
   */
  public function fetchByArticleId(string $article_id)
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE article_id = :ARTICLE_ID',
        [':ARTICLE_ID' => $article_id]
      );
  }

  /**
   * Get records by account_id
   *
   * @param  string $account_id          The Account_id
   * @return array
   */
  public function fetchByAccountId(string $account_id)
  {
    return
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE account_id = :ACCOUNT_ID',
        [':ACCOUNT_ID' => $account_id]
      );
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

    if (!empty($keywords['article_id'])) {
       $where .= 'AND (article_id = :ARTICLE_ID) ';
       $binds[':ARTICLE_ID'] = $keywords['article_id'];
    }

    if (!empty($keywords['account_id'])) {
       $where .= 'AND (account_id = :ACCOUNT_ID) ';
       $binds[':ACCOUNT_ID'] = $keywords['account_id'];
    }

    if (!empty($keywords['search'])) {
      $where .= 'AND (comment LIKE :Q1) ';
      $binds[':Q1'] = $keywords['search'];
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
        '( id, created_dt, modified_dt, uuid, article_id, account_id, comment, status) '.
        'VALUES '.
        '(:ID,:CREATED_DT,:MODIFIED_DT,:UUID,:ARTICLE_ID,:ACCOUNT_ID,:COMMENT,:STATUS)',
        [
          ':ID' => $item->getId(),
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':UUID' => $item->getUuid(),
          ':ARTICLE_ID' => $item->getArticleId(),
          ':ACCOUNT_ID' => $item->getAccountId(),
          ':COMMENT' => $item->getComment(),
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
        ' article_id = :ARTICLE_ID, '.
        ' account_id = :ACCOUNT_ID, '.
        ' comment = :COMMENT, '.
        ' status = :STATUS '.
        'WHERE '.
        ' id = :ID ',
        [
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':UUID' => $item->getUuid(),
          ':ARTICLE_ID' => $item->getArticleId(),
          ':ACCOUNT_ID' => $item->getAccountId(),
          ':COMMENT' => $item->getComment(),
          ':STATUS' => $item->getStatus(),
          ':ID' => $item->getId()
        ]
      );
  }

} // EOC


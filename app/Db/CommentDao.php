<?php
/**
 * CommentDao
 *
 * @package     Nofuzz-blog-tutorial
*/
################################################################################################################################

namespace App\Db;

class CommentDao extends \App\Db\AbstractBaseDao
{
  /**
   * Constructor
   */
  public function __construct(string $connectionName)
  {
    parent::__construct($connectionName);
    $this->setTable('blog_comments');
  }

  /**
   * Make/Generate an Entity
   *
   * @param  array  $fields [description]
   * @return object
   */
  function makeEntity(array $fields=[]): \App\Db\AbstractBaseEntity
  {
    return new \App\Db\Comment(array_change_key_case($fields),CASE_LOWER);
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
   * Get records by Account_id
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

    if (!empty($keywords['title'])) {
      $where .= 'AND (title LIKE :TITLE) ';
      $binds[':TITLE'] = $keywords['title'];
    }

    if (!empty($keywords['q'])) {
      $where .= 'AND (comment LIKE :Q1) ';
      $binds[':Q1'] = $keywords['q'];
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
   * @param  \App\Db\Article $item        [description]
   * @return bool                         True=Success, False=Failed
   */
  public function insert(\App\Db\AbstractBaseEntity &$item): bool
  {
    $id =
      $this->execCustomGetLastId(
        'INSERT INTO {table} '.
        ' ( created_dt, modified_dt, uuid, article_id, account_id, comment, status) '.
        'VALUES '.
        ' (:CREATED_DT,:MODIFIED_DT,:UUID,:ARTICLE_ID,:ACCOUNT_ID,:COMMENT,:STATUS)',
        [
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
   * Update
   *
   * @param  \App\Db\Blog $item         [description]
   * @return bool                       True=Success, False=Failed
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
        ' id = :ID',
        [
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':UUID' => $item->getUuid(),
          ':ARTICLE_ID' => $item->getArticleId(),
          ':ACCOUNT_ID' => $item->getAccountId(),
          ':COMMENT' => $item->getComment(),
          ':STATUS' => $item->getStatus()
          ':ID' => $item->getId()
        ]
      );
  }

}

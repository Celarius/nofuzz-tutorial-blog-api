<?php
/**
 * BlogArticleDao.php
 *
 *    Dao class for table blog_articles
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
 * Dao class for rows in table "blog_articles"
 *
 * @uses     \App\Db\AbstractBaseDao
 * @uses     \App\Db\AbstractBaseEntity
 */
class BlogArticleDao extends AbstractBaseDao
{
  /**
   * Constructor
   *
   * @param string  $connectionname    Database ConnectionName
   */
  public function __construct(string $connectionName)
  {
    parent::__construct($connectionName);
    $this->setTable('blog_articles');
  }

  /**
   * Make/Generate an Entity
   *
   * @param  array  $fields             Array with key=value for fields
   * @return object
   */
  function makeEntity(array $fields=[]): AbstractBaseEntity
  {
    return new \App\Db\BlogArticle(array_change_key_case($fields),CASE_LOWER);
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

    if (!empty($keywords['search'])) {
      $where .= 'AND (title LIKE :Q1 OR body LIKE :Q2) ';
      $binds[':Q1'] = $keywords['search'];
      $binds[':Q2'] = $keywords['search'];
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
        '( id, created_dt, modified_dt, uuid, blog_id, title, body, status) '.
        'VALUES '.
        '(:ID,:CREATED_DT,:MODIFIED_DT,:UUID,:BLOG_ID,:TITLE,:BODY,:STATUS)',
        [
          ':ID' => $item->getId(),
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':UUID' => $item->getUuid(),
          ':BLOG_ID' => $item->getBlogId(),
          ':TITLE' => $item->getTitle(),
          ':BODY' => $item->getBody(),
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
        ' blog_id = :BLOG_ID, '.
        ' title = :TITLE, '.
        ' body = :BODY, '.
        ' status = :STATUS '.
        'WHERE '.
        ' id = :ID ',
        [
          ':CREATED_DT' => $item->getCreatedDt(),
          ':MODIFIED_DT' => $item->getModifiedDt(),
          ':UUID' => $item->getUuid(),
          ':BLOG_ID' => $item->getBlogId(),
          ':TITLE' => $item->getTitle(),
          ':BODY' => $item->getBody(),
          ':STATUS' => $item->getStatus(),
          ':ID' => $item->getId()
        ]
      );
  }

} // EOC


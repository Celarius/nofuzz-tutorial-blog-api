<?php
/**
 * AbstractBaseDao
 *
 * @package     [app]
*/
################################################################################################################################

use \App\Db\AbstractBaseEntity as AbstractBaseEntity;

namespace App\Db;

/**
 * AbstraceBaseDao Interface
 */
interface AbstractBaseDaoInterface
{
  function makeEntity(array $fields=[]): AbstractBaseEntity;
  function fetchCustom(string $sql,array $params=[]): array;
  function execCustom(string $sql, array $params=[]): bool;
  function execCustomGetLastId(string $sql, array $params=[]): int;
  function insert(AbstractBaseEntity &$item): bool;
  function update(AbstractBaseEntity $item): bool;
  function delete(AbstractBaseEntity &$item): bool;
  function getTable(): string;
  function setTable(string $table);
  function getCacheTTL(): int;
  function setCacheTTL(int $cacheTTL=0);
}

/**
 * AbstraceBaseDao Class
 */
abstract class AbstractBaseDao extends \Nofuzz\Database\BaseDao implements AbstractBaseDaoInterface
{
  protected $table;
  protected $cacheTTL = 0;

  /**
   * Fetch all rows based on $sql and $prams
   *
   * @param  string $sql    [description]
   * @param  array  $params [description]
   * @return array
   */
  public function fetchCustom(string $sql,array $params=[]): array
  {
    # Replace {table} with the table-name
    $sql = str_replace('{table}', $this->getTable(), $sql);

    # Default to no rows returned
    $rows = [];
    try {
      $this->beginTransaction();
      # Prepare
      if ($sth=$this->db()->prepare($sql)) {
        # Binds
        foreach ($params as $bind=>$value) {
          $sth->bindValue($bind, $value);
        }
        # Exec
        if ($sth->execute()) {
          # Loop resulting rows
          while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $rows[] = $this->makeEntity($row);
          }
        }
        $sth->closeCursor();
      }
      $this->commit();
    } catch (\Exception $e) {
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e->getTraceAsString()]);
      $this->rollback();
    }
    return $rows;
  }

  /**
   * Execute $sql with $params
   *
   * @param  string $sql    [description]
   * @param  array  $params [description]
   * @return bool
   */
  public function execCustom(string $sql, array $params=[]): bool
  {
    # Replace {table} with the table-name
    $sql = str_replace('{table}', $this->getTable(), $sql);

    # Default result
    $result = false;
    try {
      $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Binds
        foreach ($params as $bind=>$value) {
          $sth->bindValue($bind, $value);
        }
        # Execute
        $result = $sth->execute();
      }
      $this->commit();
    } catch (\Exception $e) {
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e->getTraceAsString()]);
      $this->rollback();
    }
    return $result;
  }

  /**
   * Execute $sql with $params
   *
   * @param  string $sql    [description]
   * @param  array  $params [description]
   * @return int            $this->db()->lastInsertId()
   */
  public function execCustomGetLastId(string $sql, array $params=[]): int
  {
    # Replace {table} with the table-name
    $sql = str_replace('{table}', $this->getTable(), $sql);

    # Default result
    $result = 0;
    try {
      $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Binds
        foreach ($params as $bind=>$value) {
          $sth->bindValue($bind, $value);
        }
        # Execute
        if ($sth->execute()) {
          $result = $this->db()->lastInsertId();
        }
      }
      $this->commit();
    } catch (\Exception $e) {
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e->getTraceAsString()]);
      $this->rollback();

    }
    return $result;
  }

  /**
   * Fetch a record by field
   *
   * @param  string $field
   * @param  mixed $value
   * @return object | null
   */
  public function fetchBy(string $field, $value)
  {
    if ($item = $this->cacheGetItemByField($field)) return $item;

    $item =
      $this->fetchCustom(
        'SELECT * FROM {table} WHERE '.$field.' = :'.strtoupper($field),
        [':'.strtoupper($field) => $value]
      )[0] ?? null;

    if ($item) $this->cacheSetItem($item,$field);

    return $item;
  }

  /**
   * Delete
   *
   * @param  AbstractBaseEntity   &$item
   * @return bool
   */
  public function delete(AbstractBaseEntity &$item): bool
  {
    $ok =
      $this->execCustom(
        'DELETE FROM {table} WHERE id = :ID ',
        [':ID' => $item->getId()]
      );

    if ($ok) {
      $this->cacheDelete($item);
      $item->setId(0);
    }

    return $ok;
  }


  /**
   * Cache one $item
   *
   * @param  class      $item         item to Set in cache
   * @param  integer    $ttl          Optional. Override default TTL. Seconds
   * @return bool
   */
  protected function cacheSetItem(AbstractBaseEntity $item, $ttl=0 )
  {
    if ($ttl==0)
      $ttl = $this->getCacheTTL();

    if ($ttl>0 && cache() && $item) {
      # Add Item to caches
      if (method_exists($item,'getId') && $item->getId()>0) cache()->set(static::class.':id:'.$item->getId(), $item, $ttl);
      if (method_exists($item,'getUuid') && !empty($item->getUuid())) cache()->set(static::class.':uuid:'.$item->getUuid(), $item, $ttl);
      if (method_exists($item,'getCode') && !empty($item->getCode())) cache()->set(static::class.':code:'.$item->getCode(), $item, $ttl);
      if (method_exists($item,'getEmail') && !empty($item->getEmail())) cache()->set(static::class.':email:'.$item->getEmail(), $item, $ttl);
    }

    return true;
  }

  /**
   * Get cached $item, based on field name
   *
   * @param  string       $id         Item ID to look for
   * @return $item | false
   */
  protected function cacheGetItemByField(string $field)
  {
    $cacheKey = static::class.':'.$field.':'.$id;
    if (cache() && cache()->has($cacheKey)) {
      return cache()->get($cacheKey);
    }

    return false;
  }

  /**
   * Get cached $item by $id
   *
   * @param  string       $id         Item ID to look for
   * @return $item | false
   */
  protected function cacheGetById(string $id)
  {
    $cacheKey = static::class.':id:'.$id;
    if (cache() && cache()->has($cacheKey)) {
      return cache()->get($cacheKey);
    }

    return false;
  }

  /**
   * Get cached $item by $code
   *
   * @param  string       $code         Item code to look for
   * @return $item | false
   */
  protected function cacheGetByCode(string $code)
  {
    $cacheKey = static::class.':code:'.$code;
    if (cache() && cache()->has($cacheKey)) {
      return cache()->get($cacheKey);
    }

    return false;
  }

  /**
   * Get cached $item by $uuid
   *
   * @param  string       $uuid         Item uuid to look for
   * @return $item | false
   */
  protected function cacheGetByUuid(string $uuid)
  {
    $cacheKey = static::class.':uuid:'.$uuid;
    if (cache() && cache()->has($cacheKey)) {
      return cache()->get($cacheKey);
    }

    return false;
  }

#####################################################

  /**
   * Cache array of $item ($items)
   *
   * @param  array       $items [description]
   * @param  int|integer $ttl   [description]
   * @return bool
   */
  protected function cacheSetAll(array $items, int $ttl=60)
  {
    if (cache() && $items) {
      cache()->set(static::class.':all', $items, $ttl);
    }

    return true;
  }

  /**
   * Get all cached items list
   *
   * @return array|false
   */
  protected function cacheGetAll()
  {
    $cacheKey = static::class.':all';
    if (cache() && cache()->has($cacheKey)) {
      return cache()->get($cacheKey);
    }

    return false;
  }

  /**
   * Delete an $item from cache
   *
   * @param  Class    $item           Item to delete from cache
   * @return bool
   */
  protected function cacheDelete(AbstractBaseEntity $item)
  {
    if (cache() && $item) {
      # Remove Individual Item
      if (method_exists($item,'getId') && $item->getId()>0) cache()->delete(static::class.':id:'.$item->getId());
      if (method_exists($item,'getUuid') && !empty($item->getUuid())) cache()->delete(static::class.':uuid:'.$item->getUuid());
      if (method_exists($item,'getCode') && !empty($item->getCode())) cache()->delete(static::class.':code:'.$item->getCode());
      # Clear the ALL cache
      cache()->delete(static::class.':all');
    }

    return true;
  }



  /**
   * Get Table
   *
   * @return  string
   */
  public function getTable(): string
  {
    return $this->table;
  }

  /**
   * Set Table
   *
   * @param   string $table [description]
   * @return  self
   */
  public function setTable(string $table)
  {
    $this->table = $table;

    return $this;
  }

  /**
   * Get CacheTTL
   *
   * @return  int
   */
  public function getCacheTTL(): int
  {
    return $this->cacheTTL;
  }

  /**
   * Set CacheTTL
   *
   * @param   int $cacheTTL  TTL seconds
   * @return  self
   */
  public function setCacheTTL(int $cacheTTL=0)
  {
    $this->cacheTTL = $cacheTTL;

    return $this;
  }

}

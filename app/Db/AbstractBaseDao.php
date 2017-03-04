<?php
/**
 * AbstractBaseDao
 *
 * @package     Nofuzz-blog-tutorial
*/
################################################################################################################################

namespace App\Db;

interface AbstractBaseDaoInterface
{
  function makeEntity(array $fields=[]): \App\Db\AbstractBaseEntity;

  function fetchCustom(string $sql,array $params=[]): array;
  function execCustom(string $sql, array $params=[]): bool;
  function execCustomGetLastId(string $sql, array $params=[]): int;

  function insert(\App\Db\AbstractBaseEntity $item): bool;
  function update(\App\Db\AbstractBaseEntity $item): bool;
  function delete(\App\Db\AbstractBaseEntity $item): bool;

  function getTable(): string;
  function setTable(string $table);
}

abstract class AbstractBaseDao extends \Nofuzz\Database\BaseDao implements AbstractBaseDaoInterface
{
  protected $table;

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
   * Delete $item
   *
   * @param  \App\Db\AbstractBaseEntity   $item
   * @return bool
   */
  public function delete(\App\Db\AbstractBaseEntity &$item): bool
  {
    $ok = $this->execCustom(
            'DELETE FROM {table} WHERE id = :ID ',
            [':ID' => $item->getId()]
          );

    if ($ok)
      $item->setId(0);

    return $ok;
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

    return self;
  }
}

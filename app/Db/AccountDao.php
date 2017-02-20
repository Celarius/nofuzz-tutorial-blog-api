<?php
/**
 * AccountDao
 *
 * @package     Nofuzz
*/
################################################################################################################################

namespace App\Db;

interface AccountDao
{
  function fetchAll();
  function fetchById(int $id);
}

################################################################################################################################

class AccountDao extends \Nofuzz\Database\BaseDao
{
  /**
   * Fetch all records in table
   *
   * @return array
   */
  public function fetchAll()
  {
    $cacheKey = 'accounts:all';
    $cacheTTL = 30; // seconds

    # Check Cache for result
    if (cache()) {
      $records = cache()->get($cacheKey);
      if ($records) return $records;
    }

    $records = [];
    try {
      $localTrans = $this->beginTransaction();
      $sql = 'SELECT * FROM accounts';
      if ($sth=$this->db()->prepare($sql) && $sth->execute()) {
        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
          $records[] = new \App\Db\Session(array_change_key_case($row),CASE_LOWER);
        }
        # Cache it
        if (cache())
          cache()->set($cacheKey, $records, $cacheTTL);
        # Close cursor
        $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

    } catch (Exception $e) {
      # Error Logging
      logger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

      $this->rollback();
    }

    return $records;
  }


  /**
   * Get record by Id
   *
   * Cache result 'accounts:id:<id>'
   *
   * @param  int $id              The id
   * @return array                The record
   */
  public function fetchById(int $id)
  {
    $cacheKey = 'accounts:id:'.$id;
    $cacheTTL = 30; // seconds

    # Default result
    $record = [];

    # Check Cache for result
    if (cache()) {
      $record = cache()->get($cacheKey);
      if ($record) return $record;
    }

    try
    {
      # Build SQL
      $sql  = 'SELECT * FROM accounts WHERE id = :ID ';

      $localTrans = $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Bind params
        $sth->bindValue(':ID', $id, \PDO::PARAM_INT);
        # Execute
        if (($sth->execute()) && ($row = $sth->fetch(\PDO::FETCH_ASSOC)))
        {
          # Get a row
          $record = new \App\Db\Account(array_change_key_case($row),CASE_LOWER);
          # Cache it
          if (cache())
            cache()->set($cacheKey, $record, $cacheTTL);
        }
        $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

    } catch (Exception $e) {
      logger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

      $this->rollback();
    }

    return $record;
  }

  /**
   * Get record by login_name
   *
   * Cache result 'accounts:id:<id>', 'accounts:loginName:<name>'
   *
   * @param  string $loginName    The Login Name
   * @return array                The record
   */
  public function fetchByLoginName(string $loginName)
  {
    $cacheKey = 'accounts:loginName:'.$loginName;
    $cacheTTL = 30; // seconds

    # Default result
    $record = [];

    # Check Cache for result
    if (cache()) {
      $record = cache()->get($cacheKey);
      if ($record) return $record;
    }

    try
    {
      # Build SQL
      $sql  = 'SELECT * FROM accounts WHERE login_name = :LOGINNAME ';

      $localTrans = $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Bind params
        $sth->bindValue(':LOGINNAME', $loginName, \PDO::PARAM_STR);
        # Execute
        if (($sth->execute()) && ($row = $sth->fetch(\PDO::FETCH_ASSOC)))
        {
          # Get a row
          $record = new \App\Db\Account(array_change_key_case($row),CASE_LOWER);
          # Cache it
          if (cache()) {
            cache()->set($cacheKey, $record, $cacheTTL);
            cache()->set('accounts:id:'.$record['id'], $record, $cacheTTL);
          }
        }
        $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

    } catch (Exception $e) {
      logger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

      $this->rollback();
    }

    return $record;
  }


  // /**
  //  * Checks if a $sessionid exists
  //  *
  //  * Checks only sessions that have NOT expired.
  //  *
  //  * @param  string  $sessionid       The sessionid to check. if empty the propety will be used.
  //  * @return boolean                  True=Exists, False=Does NOT exist
  //  */
  // public function existsSession(string $sessionid=''): bool
  // {
  //   # Get the param, or the property
  //   $sessionid = ( strlen($sessionid)==0 ? $this->sessionid : $sessionid );

  //   # No session, no result
  //   if ( empty($sessionid) ) return false;

  //   # Default result
  //   $_sessionExists = false;

  //   # Get a connection
  //   $this->getDBConnection();

  //   # Do the db stuff
  //   if ($this->dbConnection)
  //   try {
  //     # Get Transaction status
  //     $_isLocalTransaction = !$this->dbConnection->inTransaction();

  //     # Start local transaction?
  //     if ($_isLocalTransaction) $this->dbConnection->beginTransaction();

  //     # Build SQL - Find session with $sessionid, that Expires in the future
  //     $sql  = 'SELECT ';
  //     $sql .= ' ID ';
  //     $sql .= 'FROM ';
  //     $sql .= ' WML_SESSIONS ';
  //     $sql .= 'WHERE ';
  //     $sql .= ' SESSIONID = :SID AND EXPIRES_DT >= :NOW';
  //     # Prepare
  //     if ($sth = $this->dbConnection->prepare($sql)) {
  //       $sth->bindValue(':SID', $sessionid, \PDO::PARAM_STR);
  //       $sth->bindValue(':NOW', $this->unixTimestampToStr($this->getUTCTimestampSec()), \PDO::PARAM_STR);
  //       # Exec
  //       if (($sth->execute()) && ($row = $sth->fetch(\PDO::FETCH_ASSOC))) {
  //         # if ID exists, session exists
  //         if ($row['ID']>0) $_sessionExists = true;
  //       }
  //       # Close cursor
  //       if ($sth) $sth->closeCursor();
  //     }
  //     # Commit if local transaction
  //     if ($_isLocalTransaction) $this->dbConnection->commit();

  //   } catch (Exception $e) {
  //     # Error Logging
  //     $this->getLogger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

  //     # Rollback if local transaction
  //     if ($_isLocalTransaction) $this->dbConnection->rollback();
  //   }

  //   # Return results
  //   return $_sessionExists;
  // }



  // /**
  //  * Save Session properties to the storage
  //  *
  //  * @return bool                     True=Success, False=Failed
  //  */
  // public function saveToStorage(): bool
  // {
  //   # No session, no result
  //   if ( empty($this->getSessionId()) ) return false;

  //   # Default result
  //   $_saveOk = false;

  //   # Get a connection
  //   $this->getDBConnection();

  //   # Do the db stuff
  //   if ($this->dbConnection)
  //   try {
  //     # Get Transaction status
  //     $_isLocalTransaction = !$this->dbConnection->inTransaction();

  //     # Start local transaction?
  //     if ($_isLocalTransaction) $this->dbConnection->beginTransaction();

  //     # Build SQL
  //     $sql  = 'INSERT INTO WML_SESSIONS ';
  //     $sql .= ' ( CREATED_DT, MODIFIED_DT, SESSIONID, EXTID, EXPIRES_DT, TYPEOF, SDATA, IP) ';
  //     $sql .= 'VALUES ';
  //     $sql .= ' (:CREATED_DT,:MODIFIED_DT,:SESSIONID,:EXTID,:EXPIRES_DT,:TYPEOF,:SDATA,:IP) ';
  //     if ( strcasecmp($this->dbConnection->getDriverName(),'firebird')==0 ) {
  //       $sql .= 'RETURNING ID';
  //     }
  //     # Prepare
  //     if ($sth = $this->dbConnection->prepare($sql)) {
  //       $sth->bindValue(':CREATED_DT', $this->getCreatedDt(), \PDO::PARAM_STR);
  //       $sth->bindValue(':MODIFIED_DT', $this->getModifiedDt(), \PDO::PARAM_STR);
  //       $sth->bindValue(':SESSIONID', $this->getSessionId(), \PDO::PARAM_STR);
  //       $sth->bindValue(':EXTID', $this->getExtId(), \PDO::PARAM_STR);
  //       $sth->bindValue(':EXPIRES_DT', $this->getExpiresDt(), \PDO::PARAM_STR);
  //       $sth->bindValue(':TYPEOF', $this->getTypeOf(), \PDO::PARAM_STR);
  //       $sth->bindValue(':SDATA', $this->getData(), \PDO::PARAM_STR);
  //       $sth->bindValue(':IP', $this->getIp(), \PDO::PARAM_STR);
  //       # Exec
  //       if ($sth->execute()) {

  //         if ( strcasecmp($this->dbConnection->getDriverName(),'firebird')==0 ) {
  //           # Firebird (smarter) way of getting the ID
  //           $route['ID'] = $row['ID'];
  //         } else
  //         if ( strcasecmp($this->dbConnection->getDriverName(),'mysql')==0 ) {
  //           # MySQL way of getting the ID
  //           $route['ID'] = $this->dbConnection->lastInsertId(); // MYSQL Specific
  //         }
  //         # Set OK
  //         $_saveOk = true;
  //       }
  //       # Close cursor
  //       if ($sth) $sth->closeCursor();
  //     }
  //     # Commit if local transaction
  //     if ($_isLocalTransaction) $this->dbConnection->commit();

  //   } catch (Exception $e) {
  //     # Error Logging
  //     $this->getLogger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

  //     # Rollback if local transaction
  //     if ($_isLocalTransaction) $this->dbConnection->rollback();
  //   }

  //   # Return results
  //   return $_saveOk;
  // }


  /**
   * Delete Session from storage based on the SessionId property
   *
   * @return bool                     True=Success, False=Failed
   */
  public function delete(\App\Db\Account $account): bool
  {
    # Delete from Cache(s)
    if (cache() ) {
      $cache()->delete('accounts:id:'.$account->getId());
      $cache()->delete('accounts:loginName:'.$account->getLoginName());
    }
    # Default result
    $result = false;
    try
    {
      # Build SQL
      $sql  = 'DELETE FROM accounts WHERE id = :ID ';

      $localTrans = $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Bind params
        $sth->bindValue(':ID', $account->getId(), \PDO::PARAM_INT);
        # Execute
        if ($sth->execute())
        {
          $result = true;
        }
        $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

    } catch (Exception $e) {
      logger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

      $this->rollback();
    }

    return $result;
  }

}

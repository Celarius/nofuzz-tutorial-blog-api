<?php

namespace App\Db;

class SessionsDao extends \Nofuzz\Database\BaseDao
{
  /**
   * Fetch all Sessions in table
   *
   * @return array
   */
  public function fetchAll()
  {
    $items = array();
    try {
      $localTrans = $this->beginTransaction();
      $sql = 'SELECT * FROM wml_sessions';
      if ($sth=$this->db()->prepare($sql) && $sth->execute()) {
        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
          $items[] = new \App\Db\Session(array_change_key_case($row),CASE_LOWER);
        }
        # Close cursor
        $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

    } catch (Exception $e) {
      # Error Logging
      logger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

      $this->rollback();
    }

    return $items;
  }


  /**
   * Get session from storage
   *
   * @param  string $sessionid    The session id to retreive
   * @return bool                 True for success, false for failure
   */
  public function fetchBySessionId(string $sessionid)
  {
    $session = null;

    # Check Cache for result
    if ( !empty($sessionid) && cache() ) {
      $session = cache()->get('SessionDao:sessionid:'.$sessionid);
      if ($session) return $session;
    }

    try {
      $localTrans = $this->beginTransaction();

      # Build SQL - Get $sessionid, that expires in the future
      $sql  = 'SELECT * FROM wml_sessions WHERE sessionid = :SID '; // AND expires_dt >= :NOW ';
      if ($sth = $this->db()->prepare($sql))
      {
        $sth->bindValue(':SID', $sessionid, \PDO::PARAM_STR);
        // $sth->bindValue(':NOW', (new \DateTime())->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
        if (($sth->execute()) && ($row = $sth->fetch(\PDO::FETCH_ASSOC)))
        {
          $session = new \App\Db\Session(array_change_key_case($row),CASE_LOWER);
        }
        # Close cursor
        if ($sth) $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

    } catch (Exception $e) {
      # Error Logging
      logger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

      $this->rollback();
    }

    # Cache the result
    if ( isset($session) && cache() ) {
      cache()->set('SessionDao:sessionid:'.$session->getSessionId(), $session, 10);
    }

    return $session;
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


  // /**
  //  * Delete Session from storage based on the SessionId property
  //  *
  //  * @return bool                     True=Success, False=Failed
  //  */
  // public function delete(): bool
  // {
  //   # No sessionid, no result
  //   if ( empty($this->getSessionId()) ) return false;

  //   # Default result
  //   $_ok = false;

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
  //     $sql  = 'DELETE FROM WML_SESSIONS ';
  //     $sql .= 'WHERE SESSIONID = :SESSIONID';

  //     # Prepare
  //     if ($sth = $this->dbConnection->prepare($sql)) {
  //       $sth->bindValue(':SESSIONID', $this->getSessionId(), \PDO::PARAM_STR);
  //       # Exec
  //       if ($sth->execute()) {
  //         # Set OK
  //         $_ok = true;
  //       }
  //     }
  //     # Commit if local transaction
  //     if ($_isLocalTransaction) $this->dbConnection->commit();

  //   } catch (Exception $e) {
  //     # Error Logging
  //     $this->getLogger()->critical('Exception',array('msg'=>$e->getMessage(),'trace'=>$e-getTraceAsString()));

  //     # Rollback if local transaction
  //     if ($_isLocalTransaction) $this->dbConnection->rollback();
  //   }

  //   # RDELETE FROM
  //   return $_ok;
  // }


}

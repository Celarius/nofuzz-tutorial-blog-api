<?php
/**
 * AccountDao
 *
 * @package     Nofuzz
*/
################################################################################################################################

namespace App\Db;

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
      $sql = 'SELECT * blog_FROM accounts';
      if ($sth=$this->db()->prepare($sql) && $sth->execute()) {
        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
          $records[] = new \App\Db\Account(array_change_key_case($row),CASE_LOWER);
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
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e-getTraceAsString()]);

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
   * @return null|object
   */
  public function fetchById(int $id)
  {
    $cacheKey = 'accounts:id:'.$id;
    $cacheTTL = 30; // seconds

    # Check Cache for result
    if (cache()) {
      $record = cache()->get($cacheKey);
      if ($record) return $record;
    }

    $record = null;
    try {
      # Build SQL
      $sql  = 'SELECT * FROM blog_accounts WHERE id = :ID ';

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
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e-getTraceAsString()]);

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
   * @return null|object
   */
  public function fetchByLoginName(string $loginName)
  {
    $cacheKey = 'accounts:loginName:'.$loginName;
    $cacheTTL = 30; // seconds

    # Check Cache for result
    if (cache()) {
      $record = cache()->get($cacheKey);
      if ($record) return $record;
    }

    $record = null;
    try {
      $sql  = 'SELECT * FROM blog_accounts WHERE login_name = :LOGIN_NAME ';

      $localTrans = $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Bind params
        $sth->bindValue(':LOGIN_NAME', $loginName, \PDO::PARAM_STR);
        # Execute
        if (($sth->execute()) && ($row = $sth->fetch(\PDO::FETCH_ASSOC)))
        {
          # Get a row
          $record = new \App\Db\Account(array_change_key_case($row),CASE_LOWER);
          # Cache it
          if (cache()) {
            cache()->set($cacheKey, $record, $cacheTTL);
            cache()->set('accounts:id:'.$record->getId(), $record, $cacheTTL);
          }
        }
        $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

    } catch (Exception $e) {
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e-getTraceAsString()]);

      $this->rollback();
    }

    return $record;
  }

  /**
   * Get record by email_name
   *
   * Cache result 'accounts:email:<id>'
   *
   * @param  string $loginName    The Login Name
   * @return null|object
   */
  public function fetchByEmail(string $email)
  {
    $cacheKey = 'accounts:email:'.$email;
    $cacheTTL = 30; // seconds

    # Check Cache for result
    if (cache()) {
      $record = cache()->get($cacheKey);
      if ($record) return $record;
    }

    $record = null;
    try {
      $sql  = 'SELECT * FROM blog_accounts WHERE email = :EMAIL ';

      $localTrans = $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Bind params
        $sth->bindValue(':EMAIL', $email, \PDO::PARAM_STR);
        # Execute
        if (($sth->execute()) && ($row = $sth->fetch(\PDO::FETCH_ASSOC)))
        {
          # Get a row
          $record = new \App\Db\Account(array_change_key_case($row),CASE_LOWER);
          # Cache it
          if (cache()) {
            cache()->set($cacheKey, $record, $cacheTTL);
            cache()->set('accounts:id:'.$record->getId(), $record, $cacheTTL);
          }
        }
        $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

    } catch (Exception $e) {
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e-getTraceAsString()]);

      $this->rollback();
    }

    return $record;
  }


  /**
   * Insert
   *
   * @param  \App\Db\Account $account [description]
   * @return bool                     True=Success, False=Failed
   */
  public function insert(\App\Db\Account $account): bool
  {
    $cacheTTL = 30; // seconds

    $result = false;
    try {
      $sql  = 'INSERT INTO blog_accounts '.
              ' ( uuid, login_name, first_name, last_name, email, jwt_secret, pw_salt, pw_hash, pw_iterations, status) '.
              'VALUES '.
              ' (:UUID,:LOGIN_NAME,:FIRST_NAME,:LAST_NAME,:EMAIL,:JWT_SECRET,:PW_SALT,:PW_HASH,:PW_ITERATIONS,:STATUS)';

      $localTrans = $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Bind params
        $sth->bindValue(':UUID', $account->getUuid(),\PDO::PARAM_STR);
        $sth->bindValue(':LOGIN_NAME', $account->getLoginName(),\PDO::PARAM_STR);
        $sth->bindValue(':FIRST_NAME', $account->getFirstName(),\PDO::PARAM_STR);
        $sth->bindValue(':LAST_NAME', $account->getLastName(),\PDO::PARAM_STR);
        $sth->bindValue(':EMAIL', $account->getEmail(),\PDO::PARAM_STR);
        $sth->bindValue(':JWT_SECRET', $account->getJwtSecret(),\PDO::PARAM_STR);
        $sth->bindValue(':PW_SALT', $account->getPwSalt(),\PDO::PARAM_STR);
        $sth->bindValue(':PW_HASH', $account->getPwHash(),\PDO::PARAM_STR);
        $sth->bindValue(':PW_ITERATIONS', $account->getPwIterations(),\PDO::PARAM_STR);
        $sth->bindValue(':STATUS', $account->getStatus(),\PDO::PARAM_STR);
        # Execute
        if ($sth->execute())
        {
          $account->setId( $this->db()->lastInsertId() );
          # Cache it
          if (cache()) {
            cache()->set('accounts:id:'.$account->getId(), $account, $cacheTTL);
          }
        }
        $sth->closeCursor();
      }
      if ($localTrans) {
        error_log('committing');
        $this->commit();
      }

      $result = true;

    } catch (Exception $e) {
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e-getTraceAsString()]);

      $this->rollback();
    }

    return $result;
  }


  /**
   * Update
   *
   * @param  \App\Db\Account $account [description]
   * @return bool                     True=Success, False=Failed
   */
  public function update(\App\Db\Account $account): bool
  {
    $cacheTTL = 30; // seconds

    $result = false;
    try {
      $sql  = 'UPDATE blog_accounts SET '.
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
              ' id = :ID';

      $localTrans = $this->beginTransaction();
      if ($sth = $this->db()->prepare($sql))
      {
        # Bind params
        $sth->bindValue(':UUID', $account->getUuid(),\PDO::PARAM_STR);
        $sth->bindValue(':LOGIN_NAME', $account->getLoginName(),\PDO::PARAM_STR);
        $sth->bindValue(':FIRST_NAME', $account->getFirstName(),\PDO::PARAM_STR);
        $sth->bindValue(':LAST_NAME', $account->getLastName(),\PDO::PARAM_STR);
        $sth->bindValue(':EMAIL', $account->getEmail(),\PDO::PARAM_STR);
        $sth->bindValue(':JWT_SECRET', $account->getJwtSecret(),\PDO::PARAM_STR);
        $sth->bindValue(':PW_SALT', $account->getPwSalt(),\PDO::PARAM_STR);
        $sth->bindValue(':PW_HASH', $account->getPwHash(),\PDO::PARAM_STR);
        $sth->bindValue(':PW_ITERATIONS', $account->getPwIterations(),\PDO::PARAM_STR);
        $sth->bindValue(':STATUS', $account->getStatus(),\PDO::PARAM_STR);
        $sth->bindValue(':ID', $account->getId(),\PDO::PARAM_STR);

        # Execute
        if ($sth->execute())
        {
          $account->setId( $sth->getLastInsertId() );
          # Cache it
          if (cache()) {
            cache()->set('accounts:id:'.$account->getId(), $account, $cacheTTL);
          }
        }
        $sth->closeCursor();
      }
      if ($localTrans) $this->commit();

      $result = true;

    } catch (Exception $e) {
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e-getTraceAsString()]);

      $this->rollback();
    }

    return $result;

  }

  /**
   * Delete
   *
   * @param  \App\Db\Account $account [description]
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
      $sql  = 'DELETE FROM blog_accounts WHERE id = :ID ';

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
      logger()->critical($e->getMessage(),['rid'=>app('requestId'),'trace'=>$e-getTraceAsString()]);

      $this->rollback();
    }

    return $result;
  }

}

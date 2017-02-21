/*******************************************************************************
 * Selected metadata objects
 * -------------------------
 * Extracted at 2017-02-12 20:49:37
 ******************************************************************************/

/*******************************************************************************
 * Tables
 * ------
 * Extracted at 2017-02-12 20:49:37
 ******************************************************************************/

CREATE TABLE blog_accounts (
  id            BigInt(20) NOT NULL AUTO_INCREMENT,
  created_dt    Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified_dt   Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  uuid          NVarChar(64) COLLATE utf8_general_ci,
  login_name    NVarChar(32) COLLATE utf8_general_ci,
  first_name    NVarChar(32) COLLATE utf8_general_ci,
  last_name     NVarChar(32) COLLATE utf8_general_ci,
  email         NVarChar(128) COLLATE utf8_general_ci,
  jwt_secret    NVarChar(64) COLLATE utf8_general_ci,
  pw_salt       NVarChar(128) COLLATE utf8_general_ci,
  pw_hash       NVarChar(128) COLLATE utf8_general_ci,
  pw_iterations Integer(11) DEFAULT 1,
  status        SmallInt(6) DEFAULT 0,
  PRIMARY KEY (
      id
  )
) ENGINE=InnoDB AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE blog_accounts COMMENT = '';

CREATE TABLE blog_articles (
  id          BigInt(20) NOT NULL AUTO_INCREMENT,
  created_dt  Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  uuid        NVarChar(64),
  blog_id     BigInt(20) NOT NULL,
  title       NVarChar(128) COLLATE utf8_general_ci,
  body        MediumText CHARACTER SET utf8 COLLATE utf8_general_ci,
  status      SmallInt(6) DEFAULT 0,
  PRIMARY KEY (
      id
  )
) ENGINE=InnoDB AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE blog_articles COMMENT = '';

CREATE TABLE blog_blogs (
  id          BigInt(20) NOT NULL AUTO_INCREMENT,
  created_dt  Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  uuid        NVarChar(64),
  account_id  BigInt(20) NOT NULL,
  title       NVarChar(128) COLLATE utf8_general_ci,
  description Text CHARACTER SET utf8 COLLATE utf8_general_ci,
  status      SmallInt(6) DEFAULT 0,
  PRIMARY KEY (
      id
  )
) ENGINE=InnoDB AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE blog_blogs COMMENT = '';

CREATE TABLE blog_comments (
  id          BigInt(20) NOT NULL AUTO_INCREMENT,
  created_dt  Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  uuid        NVarChar(64),
  article_id  BigInt(20) NOT NULL,
  account_id  BigInt(20) NOT NULL,
  `comment`   Text CHARACTER SET utf8 COLLATE utf8_general_ci,
  status      SmallInt(6) DEFAULT 0,
  PRIMARY KEY (
      id
  )
) ENGINE=InnoDB AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE blog_comments COMMENT = '';

CREATE TABLE blog_tokens (
  id          BigInt(20) NOT NULL AUTO_INCREMENT,
  created_dt  Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified_dt Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  sessionid   NVarChar(64) COLLATE utf8_general_ci,
  account_id  BigInt(20) NOT NULL,
  expires_dt  Timestamp,
  status      SmallInt(6) DEFAULT 0,
  PRIMARY KEY (
      id
  )
) ENGINE=InnoDB AUTO_INCREMENT=1 ROW_FORMAT=DYNAMIC DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE blog_tokens COMMENT = '';

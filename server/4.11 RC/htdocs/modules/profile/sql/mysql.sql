CREATE TABLE `profile_category` (
  `cat_id`          smallint(5) unsigned    NOT NULL auto_increment,
  `cat_title`       varchar(255)            NOT NULL default '',
  `cat_description` text,
  `cat_weight`      smallint(5) unsigned    NOT NULL default '0',
  
  PRIMARY KEY  (`cat_id`)
) ENGINE=INNODB;

CREATE TABLE `profile_field` (
  `field_id`            int(12) unsigned        NOT NULL auto_increment,
  `cat_id`              smallint(5) unsigned    NOT NULL default '0',
  `field_type`          varchar(30)             NOT NULL default '',
  `field_valuetype`     tinyint(2) unsigned     NOT NULL default '0',
  `field_name`          varchar(255)            NOT NULL default '',
  `field_title`         varchar(255)            NOT NULL default '',
  `field_description`   text,
  `field_required`      tinyint(1) unsigned     NOT NULL default '0',
  `field_maxlength`     smallint(6) unsigned    NOT NULL default '0',
  `field_weight`        smallint(6) unsigned    NOT NULL default '0',
  `field_default`       text,
  `field_notnull`       tinyint(1) unsigned     NOT NULL default '0',
  `field_edit`          tinyint(1) unsigned     NOT NULL default '0',
  `field_show`          tinyint(1) unsigned     NOT NULL default '0',
  `field_config`        tinyint(1) unsigned     NOT NULL default '0',
  `field_options`       text,
  `step_id`             smallint(3) unsigned    NOT NULL default '0',
  
  PRIMARY KEY  (`field_id`),
  UNIQUE KEY `field_name` (`field_name`),
  KEY `step` (`step_id`, `field_weight`)
) ENGINE=INNODB;

CREATE TABLE `profile_visibility` (
  `field_id`        int(12) unsigned        NOT NULL default '0',
  `user_group`      smallint(5) unsigned    NOT NULL default '0',
  `profile_group`   smallint(5) unsigned    NOT NULL default '0',
  
  PRIMARY KEY (`field_id`, `user_group`, `profile_group`),
  KEY `visible` (`user_group`, `profile_group`)
) ENGINE=INNODB;

CREATE TABLE `profile_regstep` (
  `step_id`         smallint(3) unsigned    NOT NULL auto_increment,
  `step_name`       varchar(255)            NOT NULL DEFAULT '',
  `step_desc`       text,
  `step_order`      smallint(3) unsigned    NOT NULL default '0',
  `step_save`       tinyint(1) unsigned     NOT NULL default '0',
  
  PRIMARY KEY (`step_id`),
  KEY `sort` (`step_order`, `step_name`)
) ENGINE=INNODB;

CREATE TABLE `profile_profile` (
  `profile_id`      int(12) unsigned        NOT NULL default '0',
  
  PRIMARY KEY  (`profile_id`)
) ENGINE=INNODB;

CREATE TABLE `profile_validation` (
	`rule_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
	`weight` tinyint(5) unsigned DEFAULT '1',
	`type` enum('regex','sql','match') DEFAULT NULL,
	`action` tinytext,
	PRIMARY KEY (`rule_id`)
) ENGINE=MyISAM;

CREATE TABLE `profile_oauth_applet` (
  `appid` int(12) unsigned NOT NULL auto_increment,
  `profile_id` int(12) unsigned NOT NULL default '0'
  `name` TEXT, 
  `description` TEXT,
  `image` TEXT, 
  `uri` TEXT,
  `client_id` TEXT,
  `org_name` TEXT, 
  `org_uri` TEXT,
  PRIMARY KEY  (`appid`)
) ENGINE=MyISAM;

CREATE TABLE `profile_oauth_clients` (
  `client_id` TEXT, 
  `client_secret` TEXT, 
  `redirect_uri` TEXT
) ENGINE=MyISAM;

CREATE TABLE `profile_oauth_access_tokens` (
  `access_token` TEXT, 
  `client_id` TEXT,
  `user_id` TEXT, 
  `expires` TIMESTAMP, 
  `scope` TEXT
) ENGINE=MyISAM;

CREATE TABLE `profile_oauth_authorization_codes` (
  `authorization_code` TEXT, 
  `client_id` TEXT, 
  `user_id` TEXT, 
  `redirect_uri` TEXT, 
  `expires` TIMESTAMP, 
  `scope` TEXT
) ENGINE=MyISAM;

CREATE TABLE `profile_oauth_refresh_tokens` (
  `refresh_token` TEXT, 
  `client_id` TEXT, 
  `user_id` TEXT, 
  `expires` TIMESTAMP, 
  `scope` TEXT
) ENGINE=MyISAM;

CREATE TABLE `profile_oauth_jwt` (
  `client_id` TEXT, 
  `subject` TEXT, 
  `public_key` TEXT
) ENGINE=MyISAM;

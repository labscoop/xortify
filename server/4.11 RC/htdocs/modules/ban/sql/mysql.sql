CREATE TABLE `ban_categories` (                         
	`category_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  
	`category_name` VARCHAR(128) DEFAULT NULL,               
	PRIMARY KEY (`category_id`)                              
) ENGINE=MYISAM ;

CREATE TABLE `ban_member` (                           
  `member_id` int(10) unsigned NOT NULL auto_increment,
  `category_id` int(10) unsigned default NULL,
  `email` varchar(255) default NULL,
  `uid` int(12) default '0',
  `uname` varchar(64) default NULL,
  `ip4` varchar(255) default NULL,
  `ip6` tinytext,
  `long` varchar(120) default NULL,
  `proxy-ip4` varchar(255) default NULL,
  `proxy-ip6` tinytext,
  `network-addy` varchar(255) default NULL,
  `mac-addy` varchar(255) default NULL,
  `made` int(12) default '0',
  `country-code` varchar(3) default NULL,
  `country-name` varchar(128) default NULL,
  `region-name` varchar(128) default NULL,
  `city-name` varchar(128) default NULL,
  `postcode` varchar(15) default NULL,
  `latitude` decimal(5,5) default '0.00000',
  `longitude` decimal(5,5) default '0.00000',
  `time-zone` varchar(6) default '00:00',
  PRIMARY KEY  (`member_id`),
  KEY `SEARCH` (`category_id`,`uname`(10),`proxy-ip4`(15),`proxy-ip6`(64),`country-code`,`postcode`(10),`latitude`,`longitude`,`time-zone`)                            
) ENGINE=MYISAM ;